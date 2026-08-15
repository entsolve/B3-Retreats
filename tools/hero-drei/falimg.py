#!/usr/bin/env python3
"""fal.ai image generation via the same queue helpers as motion.py.

  python3 falimg.py --prompt-file p.txt --ref a.png --ref b.png \
      --out out/v1 --n 3 --aspect 16:9 --model nbpro
"""
import argparse, json, os, pathlib, sys, urllib.request

sys.path.insert(0, os.path.expanduser("~/Documents/entsolve/GIT/polarholz-3drenders"))
import generate as g
import motion as m

MODELS = {
    "nbpro":   "fal-ai/nano-banana-pro/edit",
    "nbproT2I": "fal-ai/nano-banana-pro",
    "nb":      "fal-ai/gemini-25-flash-image/edit",
    "kontext": "fal-ai/flux-pro/kontext/max/multi",
    "ultra":   "fal-ai/flux-pro/v1.1-ultra",
    "seedream": "fal-ai/bytedance/seedream/v4/edit",
}


def main():
    g.load_env(pathlib.Path(g.HERE) / ".env")
    ap = argparse.ArgumentParser()
    ap.add_argument("--prompt")
    ap.add_argument("--prompt-file")
    ap.add_argument("--ref", action="append", default=[])
    ap.add_argument("--out", required=True)
    ap.add_argument("--n", type=int, default=1)
    ap.add_argument("--aspect", default="16:9")
    ap.add_argument("--resolution", default="2K")
    ap.add_argument("--model", default="nbpro")
    ap.add_argument("--seed", type=int, default=None)
    a = ap.parse_args()

    key = os.environ.get("FAL_KEY")
    if not key:
        sys.exit("FAL_KEY not found in .env")
    prompt = a.prompt or pathlib.Path(a.prompt_file).read_text()
    slug = MODELS.get(a.model, a.model)

    body = {"prompt": prompt, "num_images": a.n, "output_format": "png"}
    if a.ref:
        body["image_urls"] = [m.upload(r, key, debug=True) for r in a.ref]
    if "flux-pro/v1.1" in slug or "kontext" in slug:
        body.pop("output_format", None)
        body["aspect_ratio"] = a.aspect
    else:
        body["aspect_ratio"] = a.aspect
        body["resolution"] = a.resolution
    if a.seed is not None:
        body["seed"] = a.seed

    print(f"model: {slug}")
    sub = m.submit(slug, body, key, debug=False)
    resp_url, _ = m.poll(sub.get("status_url"), sub.get("response_url"), slug,
                         sub.get("request_id"), key)
    out = m._open(m._req(resp_url, key))
    out = out.get("response", out)
    imgs = out.get("images") or []
    if not imgs:
        print(json.dumps(out)[:800]); sys.exit(6)
    outp = pathlib.Path(a.out)
    outp.parent.mkdir(parents=True, exist_ok=True)
    for i, im in enumerate(imgs, 1):
        dst = outp.with_name(f"{outp.name}-{i}.png")
        dst.write_bytes(urllib.request.urlopen(im["url"]).read())
        print(f"saved {dst}")


if __name__ == "__main__":
    main()
