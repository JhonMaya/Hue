<?php exit() ?>--by vadash 108.162.254.25
_G.loadit = function (env, chunk)
    local name = tostring(math.random(100,999)).."script"..tostring(math.random(10,99))
    local name2 = tostring(math.random(100,999)).."script"..tostring(math.random(10,99))
    local E = load(Base64Decode(chunk), name, name2, env)
    E()
end