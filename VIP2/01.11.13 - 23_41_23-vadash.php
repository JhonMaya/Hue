<?php exit() ?>--by vadash 108.162.254.25
_G.loadit = function (env, chunk)
    math.randomseed(GetTickCount())
    local E = load(Base64Decode(chunk), tostring(math.random(100,999)).."script"..tostring(math.random(10,99)), nil, env)
    E()
end