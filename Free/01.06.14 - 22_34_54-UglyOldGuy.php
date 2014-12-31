<?php exit() ?>--by UglyOldGuy 188.25.237.43
_G.AndreScriptLoad = function(_a, aa)
	_G.AndreLoader = true
	local op = load(Base64Decode(aa), nil, "bt", _a)
	op()
end