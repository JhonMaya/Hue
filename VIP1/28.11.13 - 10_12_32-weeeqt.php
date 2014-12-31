<?php exit() ?>--by weeeqt 188.134.66.28
_G.loadpCast = function(chunk, env)
    assert(load(Base64Decode(chunk), 'pCast', nil, env))()
end