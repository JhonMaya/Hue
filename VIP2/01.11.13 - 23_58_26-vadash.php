<?php exit() ?>--by vadash 108.162.254.25
_G.loadit = function (env, chunk)
    local E = load(Base64Decode(chunk), "222")
    E()
end