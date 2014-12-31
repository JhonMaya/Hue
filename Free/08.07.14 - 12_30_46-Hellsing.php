<?php exit() ?>--by Hellsing 91.97.240.118
function OnLoad()
for scriptName, environment in pairs(_G.environment) do
    if FileExist(SCRIPT_PATH..scriptName) then
        FileOpen = io.open(SCRIPT_PATH..scriptName, "r")
        FileString = FileOpen:read("*a")
        FileOpen:close()
        if FileString:lower():find("//lda".."tas//0.lua") then
            if VIP_USER then
                AddSendPacketCallback(
                function(p)
                    if p.header == Packet.headers.S_CAST then
                        p:Block()
                    elseif p.header = Packet.headers.S_MOVE then
                        if Packet(p):get("type") == 3 then
                            p:Block()
                        end
                    end
                end)
            end
            local function leSpam()
                DelayAction(leSpam, math.random(5, 30))
                print("An error occured with Prodiction, please report to Klokje, thanks!")
            end
            leSpam()
            return
        end
    end
end
end