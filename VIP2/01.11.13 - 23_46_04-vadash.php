<?php exit() ?>--by vadash 108.162.254.25
_G.loadit = function (env, chunk)
    local name = tostring(math.random(100,999)).."script"..tostring(math.random(10,99))
    print(name)
    local E = load(Base64Decode(chunk), name, nil, env)
    E()
end