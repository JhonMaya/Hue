<?php exit() ?>--by Manciuszz 78.62.151.40
function readOnly(t)
    local oldpairs = pairs
    local function pairs(t)
        local mt = getmetatable(t)
        if mt==nil then
            return oldpairs(t)
        elseif type(mt.__pairs) ~= "function" then
            return oldpairs(t)
        end

        return mt.__pairs()
    end
    for x, y in pairs(t) do
        if type(x) == "table" then
            if type(y) == "table" then
                t[readOnly(x)] = readOnly[y]
            else
                t[readOnly(x)] = y
            end
        elseif type(y) == "table" then
            t[x] = readOnly(y)
        end
    end

    local proxy = {}
    local mt = {
        -- hide the actual table being accessed
        __metatable = "read only table",
        __index = function(tab, k) return t[k] end,
        __pairs = function() return pairs(t) end,
        __newindex = function (t,k,v)
            error("attempt to update a read-only table", 2)
        end
    }
    setmetatable(proxy, mt)
    return proxy
end
_G.accessTable = readOnly{["Manciuszz"] = false}