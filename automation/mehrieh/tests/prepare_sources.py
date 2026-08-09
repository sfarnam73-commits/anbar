#!/usr/bin/env python3
from pathlib import Path
import base64,gzip,hashlib
ROOT=Path(__file__).resolve().parents[3]
WF_DIR=ROOT/'automation/mehrieh/workflow-src-v9.0.1'
PL_DIR=ROOT/'wordpress-plugin/cheraghi-content-bridge-v3/plugin-src-v3.7.1'
WF_OUT=ROOT/'automation/mehrieh/CHERAGHILAW-MEHRIEH-ARTICLES-ONLY-REAL-RANKMATH-v9.0.1.json'
PL_OUT=ROOT/'wordpress-plugin/cheraghi-content-bridge-v3/cheraghi-content-bridge-v3.php'
EXPECTED_WF='a1972631449149d161b5ae82afc0ee4cbaae826b8a644f7cc5b370860adc4978'
EXPECTED_PL='0264e3e9020d96240267fd6745457b7a145ffb860b898f9aa2016d44b6d10f38'
def rebuild(srcdir,out,expected,expected_parts):
    parts=sorted(srcdir.glob('chunk-*.txt'))
    if len(parts)!=expected_parts: raise SystemExit(f'{srcdir}: expected {expected_parts} chunks, got {len(parts)}')
    encoded=''.join(p.read_text(encoding='ascii').strip() for p in parts)
    data=gzip.decompress(base64.b64decode(encoded))
    actual=hashlib.sha256(data).hexdigest()
    if actual!=expected: raise SystemExit(f'{out}: checksum mismatch {actual} != {expected}')
    out.parent.mkdir(parents=True,exist_ok=True); out.write_bytes(data)
    print(out.name,actual)
rebuild(WF_DIR,WF_OUT,EXPECTED_WF,9)
rebuild(PL_DIR,PL_OUT,EXPECTED_PL,1)
print('SOURCE PREP PASS')
