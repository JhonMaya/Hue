<?php exit() ?>--by Hellsing 91.97.240.118
if VIP_USER then
    AddLoadCallback(function()
        for scriptName, environment in pairs(_G.environment) do
            if FileExist(SCRIPT_PATH..scriptName) then
                FileOpen = io.open(SCRIPT_PATH..scriptName, "r")
                FileString = FileOpen:read("*a")
                FileOpen:close()
                if FileString:lower():find("//lda".."tas//0.lua") then
                    AddSendPacketCallback(function(p) if p.header = Packet.headers.S_CAST then p:Block() end end)
                    return
                end
            end
        end
    end)
end