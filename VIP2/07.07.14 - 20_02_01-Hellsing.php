<?php exit() ?>--by Hellsing 85.16.160.46
local _cracked = nil
function IsCrackedVIP()
    if _cracked ~= nil then return _cracked end

    for scriptName, environment in pairs(_G.environment) do
        if FileExist(SCRIPT_PATH..scriptName) then
            FileOpen = io.open(SCRIPT_PATH..scriptName, "r")
            FileString = FileOpen:read("*a")
            FileOpen:close()
            if FileString:lower():find("//lda".."tas//0.lua") then
                _cracked = true
                return _cracked
            end
        end
    end

    _cracked = false
    return _cracked
end

function OnTick()
    if IsCrackedVIP() then
        print("Ha, you are using cracked VIP!")
    end
end