#!/usr/bin/env python3
import base64, gzip, hashlib
from pathlib import Path

ROOT = Path(__file__).resolve().parents[3]
WF_DIR = ROOT / 'automation/mehrieh/workflow-src'
WF_OUT = ROOT / 'automation/mehrieh/CHERAGHILAW-MEHRIEH-ARTICLES-ONLY-REAL-RANKMATH-v9.0.0.json'
PLUGIN_B64 = ROOT / 'wordpress-plugin/cheraghi-content-bridge-v3/cheraghi-content-bridge-v3.php.gz.b64'
PLUGIN_OUT = ROOT / 'wordpress-plugin/cheraghi-content-bridge-v3/cheraghi-content-bridge-v3.php'
HARNESS_B64 = ROOT / 'automation/mehrieh/tests/bridge_harness.php.gz.b64'
HARNESS_OUT = ROOT / 'automation/mehrieh/tests/bridge_harness.php'

EXPECTED = {
    WF_OUT: '0e4e423e0d04da330926a72687c93cfc4f3196aa38307f09bb61b03705ea88e7',
    PLUGIN_OUT: 'bec81506529ddf7dbe60899e0d92d5d37b9ec9cdcae0b9818aadc53a21330e41',
}

def decode_gzip_b64(text: str) -> bytes:
    return gzip.decompress(base64.b64decode(''.join(text.split())))

def write_checked(path: Path, data: bytes):
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_bytes(data)
    expected = EXPECTED.get(path)
    if expected:
        actual = hashlib.sha256(data).hexdigest()
        if actual != expected:
            raise SystemExit(f'checksum mismatch for {path}: {actual} != {expected}')

parts = sorted(WF_DIR.glob('part-*.txt'))
if not parts:
    raise SystemExit('workflow source parts not found')
workflow_b64 = ''.join(p.read_text(encoding='utf-8').strip() for p in parts)
write_checked(WF_OUT, decode_gzip_b64(workflow_b64))
write_checked(PLUGIN_OUT, decode_gzip_b64(PLUGIN_B64.read_text(encoding='utf-8')))
write_checked(HARNESS_OUT, decode_gzip_b64(HARNESS_B64.read_text(encoding='utf-8')))
print('SOURCE PREP PASS')
print('workflow_sha256=' + hashlib.sha256(WF_OUT.read_bytes()).hexdigest())
print('plugin_sha256=' + hashlib.sha256(PLUGIN_OUT.read_bytes()).hexdigest())
