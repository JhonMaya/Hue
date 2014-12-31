<?php exit() ?>--by Manciuszz 78.62.151.40
function readOnly(t)
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

_G.M123WVZ_Y4MZY_U5_5QU_KDAR_HG123 = readOnly{ --Note: Lowercase only
    ['manciuszz'] = {true, 'NHFK-BZRF-L-CP-KFLL-QH'},
    ['frd'] = {true, 'XFKU-QCYA-E-BQ-ECFV-SG'},
}