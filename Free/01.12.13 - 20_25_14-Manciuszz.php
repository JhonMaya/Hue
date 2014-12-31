<?php exit() ?>--by Manciuszz 78.62.151.40
_G.readOnly = function(t)
    local proxy = {}
    local mt = {       -- create metatable
        __index = t,
        __newindex = function (t,k,v)
            print("ACCESS DENIED -> No permissions to run the script!")
        end
    }
    setmetatable(proxy, mt)
    return proxy
end

_G.accessTable = _G.readOnly{["Manciuszz"] = false,}