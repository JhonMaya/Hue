<?php exit() ?>--by xetrok 27.122.126.1
_G.BolAuthInject = function(str, e) assert(load(Base64Decode("' .. str .. '"), nil, "bt", e))() end