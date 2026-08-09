#!/usr/bin/env python3
import json,re,html,sys,subprocess,tempfile,os
from pathlib import Path
ROOT=Path(__file__).resolve().parents[3]
WF=ROOT/'automation/mehrieh/CHERAGHILAW-MEHRIEH-ARTICLES-ONLY-REAL-RANKMATH-v9.0.0.json'
PLUGIN=ROOT/'wordpress-plugin/cheraghi-content-bridge-v3/cheraghi-content-bridge-v3.php'
w=json.load(open(WF,encoding='utf-8')); php=PLUGIN.read_text(encoding='utf-8'); errors=[]
# graph + JS syntax
names=[n['name'] for n in w['nodes']]
if len(names)!=len(set(names)): errors.append('duplicate node names')
for src,conn in w.get('connections',{}).items():
    if src not in names: errors.append('missing connection source '+src)
    for branch in conn.get('main',[]):
        for d in branch:
            if d['node'] not in names: errors.append(f'{src} -> missing {d["node"]}')
for n in w['nodes']:
    if n['type']=='n8n-nodes-base.code':
        f=tempfile.NamedTemporaryFile('w',suffix='.js',delete=False,encoding='utf-8')
        f.write('(function(){\n'+n['parameters'].get('jsCode','')+'\n});\n'); f.close()
        r=subprocess.run(['node','--check',f.name],capture_output=True,text=True); os.unlink(f.name)
        if r.returncode: errors.append(n['name']+': '+r.stderr.strip())
# extract exact embedded pack
load=next(n for n in w['nodes'] if n['name']=='بارگذاری ۲۵ مقاله تمیز 006 تا 030')
m=re.search(r'const articles = (\[.*\]); return',load['parameters']['jsCode'],re.S)
if not m: errors.append('cannot parse embedded article pack'); arts=[]
else: arts=json.loads(m.group(1))
expected=[f'mehrieh-{i:03d}' for i in range(6,31)]
if [a.get('article_id') for a in arts]!=expected: errors.append('article IDs/order are not 006..030')
if len(set(a.get('focus_keyword') for a in arts))!=25: errors.append('primary focus keywords are not unique')
def plain(s): return html.unescape(re.sub(r'\s+',' ',re.sub(r'<[^>]+>',' ',s or ''))).strip()
for a in arts:
    aid=a['article_id']; c=a['content_html']; t=plain(c); words=max(1,len(t.split())); focus=a['focus_keyword']; density=t.count(focus)/words*100
    for bad in ['seo_audit','seo_runtime_requirements','internal_seo_score','uniqueness_score','rank_math_readiness_score']:
        if bad in a: errors.append(f'{aid}: synthetic field {bad}')
    for bad in ['CHERAGHI-SEO-AUTO','CHERAGHI-RANKMATH-EDITORIAL','cheraghi-law-premium/assets/images']:
        if bad in c: errors.append(f'{aid}: old padding/dependency {bad}')
    if 'دکتر فرزانه چراغی، وکیل همدان' not in t: errors.append(f'{aid}: brand keyword missing')
    if a.get('rank_math_focus_keyword') != focus+',دکتر فرزانه چراغی، وکیل همدان': errors.append(f'{aid}: Rank Math primary+brand keyword mismatch')
    if not a['seo_title'].startswith(focus): errors.append(f'{aid}: SEO title does not start with focus')
    if focus not in a['meta_description']: errors.append(f'{aid}: meta does not contain focus')
    if not 0.76 <= density <= 2.5: errors.append(f'{aid}: focus density {density:.2f}')
    if len(a['seo_title'])>65: errors.append(f'{aid}: SEO title length {len(a["seo_title"])}')
    if not 115<=len(a['meta_description'])<=158: errors.append(f'{aid}: meta length {len(a["meta_description"])}')
    if 'wp-block-rank-math-toc-block' not in c: errors.append(f'{aid}: TOC marker missing')
    if len(re.findall(r'<a\b[^>]*href=["\']https?://(?:www\.)?cheraghilaw\.ir/',c,re.I))<2: errors.append(f'{aid}: <2 internal HTML links')
    followed=0
    for lm in re.finditer(r'<a\b([^>]*)href=["\'](https?://[^"\']+)["\']([^>]*)>',c,re.I):
        if 'cheraghilaw.ir' not in lm.group(2) and 'nofollow' not in (lm.group(1)+' '+lm.group(3)).lower(): followed+=1
    if followed<1: errors.append(f'{aid}: no followed external source')
    for q in a.get('faq',[]):
        if q.get('question') not in t or q.get('answer') not in t: errors.append(f'{aid}: visible FAQ/schema source mismatch')
    schema=a.get('schema') or []; art=next((x for x in schema if x.get('@type')=='Article'),None)
    if not art or int(art.get('wordCount',-1))!=words or art.get('description')!=a['meta_description']: errors.append(f'{aid}: Article schema mismatch')
# no obsolete score gate or direct wp core publish
raw=WF.read_text(encoding='utf-8')
for bad in ['minimum_internal_seo_score','minimum_uniqueness_score','minimum_rank_math_readiness_score','SEO_READINESS_FAILED','/wp-json/wp/v2/posts']:
    if bad in raw: errors.append('workflow obsolete token '+bad)
# plugin static contract
for bad in ['rank_math_readiness_audit','prepare_article_for_blog','build_focus_summary_block','build_contextual_media_block',"update_post_meta($post_id, 'rank_math_seo_score'"]:
    if bad in php: errors.append('plugin obsolete behavior '+bad)
if "'synthetic_score_gate' => false" not in php or "'rank_math_actual_score_only' => true" not in php: errors.append('plugin real-score contract missing')
if 'add_option($lock_name' not in php or 'get_today_published_project_post' not in php: errors.append('plugin daily lock missing')
pairs=dict(re.findall(r"'(mehrieh-\d{3})'\s*=>\s*'([^']+)'",php)); expected_slugs={a['article_id']:a['slug'] for a in arts}
if pairs!=expected_slugs: errors.append('plugin legacy slug map != article pack')
if errors:
    print('VALIDATION FAILED',len(errors)); [print(' -',e) for e in errors]; sys.exit(1)
print('VALIDATION PASS')
print(f'nodes={len(w["nodes"])} code_nodes={sum(n["type"]=="n8n-nodes-base.code" for n in w["nodes"])} articles={len(arts)}')
print('synthetic_score_gate=false; published_overwrite_default=false; daily_publish_limit=1')
