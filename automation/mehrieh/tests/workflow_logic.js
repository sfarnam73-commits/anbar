const fs=require('fs');
const w=JSON.parse(fs.readFileSync(require('path').resolve(__dirname,'../CHERAGHILAW-MEHRIEH-ARTICLES-ONLY-REAL-RANKMATH-v9.0.0.json'),'utf8'));
const nodes=Object.fromEntries(w.nodes.filter(n=>n.type==='n8n-nodes-base.code').map(n=>[n.name,n]));
function run(name,json={},ctx={}){
  const code=nodes[name].parameters.jsCode;
  const dollar=(nodeName)=>({first:()=>({json:ctx[nodeName]||{}})});
  const f=new Function('$json','$','return (function(){'+code+'\n})();');
  return f(json,dollar);
}
function ok(c,m){if(!c){console.error('FAIL:',m);process.exit(1)} console.log('PASS:',m)}

const manual=run('حالت دستی = Dry Run'); ok(manual[0].json.run_mode==='dry_run','manual mode is dry_run');
const sched=run('حالت زمان‌بندی = Publish'); ok(sched[0].json.run_mode==='publish','schedule mode is publish');
const cfg=run('تنظیمات پروژه بدون امتیاز ساختگی',sched[0].json)[0].json;
ok(cfg.minimum_bridge_version==='3.7.0','settings require Bridge 3.7.0');
const status={ok:true,ready:true,version:'3.7.0',synthetic_score_gate:false,rank_math_actual_score_only:true,published_overwrite_default:false,can_edit_posts:true,can_publish_posts:true,rank_math_active:true,post_type_target:'post',content_hub_target:'articles',category_slugs_target:['legal-articles','family-mahrieh'],academy_post_type_used:false,academy_categories_used:false,published_article_ids:[],published_today:false};
const sg=run('تأیید قرارداد واقعی Bridge',status,{'تنظیمات پروژه بدون امتیاز ساختگی':cfg}); ok(sg[0].json.bridge_gate_passed===true,'Bridge status gate passes valid 3.7 contract');
let rejected=false;try{run('تأیید قرارداد واقعی Bridge',{...status,synthetic_score_gate:true},{'تنظیمات پروژه بدون امتیاز ساختگی':cfg});}catch(e){rejected=String(e).includes('synthetic_score_gate')} ok(rejected,'Bridge status gate rejects synthetic score contract');
const load=run('بارگذاری ۲۵ مقاله تمیز 006 تا 030',{}, {'تنظیمات پروژه بدون امتیاز ساختگی':cfg})[0].json; ok(load.articles.length===25,'25 articles embedded');
let sel=run('انتخاب اولین مقاله منتشرنشده',load,{'بررسی Bridge 3.7 و وضعیت واقعی':status,'تنظیمات پروژه بدون امتیاز ساختگی':cfg})[0].json; ok(sel.state==='ready'&&sel.article.article_id==='mehrieh-006','selects first unpublished article');
for(const a of load.articles){
  const x={state:'ready',run_mode:'publish',article:a,idempotency_key:'x'};
  const out=run('کنترل محتوای واقعی مقاله',x)[0].json; ok(out.preflight_ok===true,'preflight '+a.article_id);
}
const dryCfg=run('تنظیمات پروژه بدون امتیاز ساختگی',manual[0].json)[0].json;
let drySel=run('انتخاب اولین مقاله منتشرنشده',load,{'بررسی Bridge 3.7 و وضعیت واقعی':{...status,published_today:true,published_today_article_id:'mehrieh-005'},'تنظیمات پروژه بدون امتیاز ساختگی':dryCfg})[0].json;
ok(drySel.state==='ready','manual dry run can inspect next article even if today already published');
const dryPre=run('کنترل محتوای واقعی مقاله',{...drySel,run_mode:'dry_run'})[0].json;
const dryRes=run('نتیجه Dry Run — هیچ تغییری در سایت نداد',dryPre)[0].json; ok(dryRes.result==='DRY_RUN_OK','manual dry run terminates without WordPress write');
const dailySel=run('انتخاب اولین مقاله منتشرنشده',load,{'بررسی Bridge 3.7 و وضعیت واقعی':{...status,published_today:true,published_today_article_id:'mehrieh-006',published_today_post_id:77},'تنظیمات پروژه بدون امتیاز ساختگی':cfg})[0].json; ok(dailySel.state==='daily_limit','schedule respects published_today');
const dailyEnd=run('پایان بدون انتشار',dailySel)[0].json; ok(dailyEnd.result==='NO_PUBLISH_TODAY','daily limit ends without publishing');
const almostDone={...status,published_article_ids:load.articles.slice(0,24).map(a=>a.article_id)};
const last=run('انتخاب اولین مقاله منتشرنشده',load,{'بررسی Bridge 3.7 و وضعیت واقعی':almostDone,'تنظیمات پروژه بدون امتیاز ساختگی':cfg})[0].json; ok(last.article.article_id==='mehrieh-030','queue advances by real published IDs');
const doneStatus={...status,published_article_ids:load.articles.map(a=>a.article_id)};
const done=run('انتخاب اولین مقاله منتشرنشده',load,{'بررسی Bridge 3.7 و وضعیت واقعی':doneStatus,'تنظیمات پروژه بدون امتیاز ساختگی':cfg})[0].json; ok(done.state==='complete','queue completes at 25 published IDs');
const pre={...sel,preflight_ok:true};
const imp=run('تأیید Import بدون دستکاری Published',{ok:true,post_id:99,status:'draft',operation:'created_draft'},{'کنترل محتوای واقعی مقاله':pre})[0].json; ok(imp.post_id===99&&imp.article.article_id==='mehrieh-006','import evaluator preserves selected article');
const ver=run('تأیید Verify واقعی',{ok:true,ready:true,post_id:99,status:'draft',post_type:'post',content_hub:'articles',category_slugs:['legal-articles','family-mahrieh'],checks:{}},{'تأیید Import بدون دستکاری Published':imp})[0].json; ok(ver.post_id===99,'verify evaluator accepts real structural response without score');
const pub=run('نتیجه نهایی و نمره واقعی Rank Math',{ok:true,status:'publish',post_id:99,article_id:'mehrieh-006',rank_math_seo_score:null,rank_math_score_fresh:false,rank_math_score_state:'not_calculated'},{'تأیید Verify واقعی':ver})[0].json; ok(pub.result==='PUBLISHED_OK'&&pub.rank_math_seo_score===null,'publish evaluator accepts null rather than inventing score');
const protectedImp=run('تأیید Import بدون دستکاری Published',{ok:true,post_id:88,status:'publish',operation:'protected_existing_published',article_id:'mehrieh-006',permalink:'x'},{'کنترل محتوای واقعی مقاله':pre})[0].json;
const protectedEnd=run('پایان امن — Published دست‌نخورده ماند',protectedImp)[0].json; ok(protectedEnd.result==='PROTECTED_EXISTING_PUBLISHED','protected Published path terminates safely');
console.log('ALL WORKFLOW LOGIC TESTS PASSED');
