import sys, pickle, numpy as np
with open('../Cancer.ipynb','rb') as f: pl=pickle.load(f)
inpf=[float(x) for x in sys.argv[1:]]
pred=pl['model'].predict(pl['preprocessor'].transform([inpf]))[0]
print(pred)
