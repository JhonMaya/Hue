<?php exit() ?>--by UglyOldGuy 188.25.237.43
_G.AndreScriptLoad = function(d)
	_G.AndreLoader = true
	assert(load(Base64Decode(d), nil, "bt", _ENV))()
end