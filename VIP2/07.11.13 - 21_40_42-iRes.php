<?php exit() ?>--by iRes 141.101.99.186
function OnLoad()
    PrintChat(">> Chat Translator loaded")
    config = scriptConfig("Chat Translator", "Config")
    config:addParam("enabled", "Enable?", SCRIPT_PARAM_ONOFF, true)
    config:addParam("info", "Only pick one! (deselect your last choice first)", SCRIPT_PARAM_INFO, "")
    config:addParam("german", "Language: German", SCRIPT_PARAM_ONOFF, false)
    config:addParam("english", "Language: English", SCRIPT_PARAM_ONOFF, true)
    config:addParam("dutch", "Language: Dutch", SCRIPT_PARAM_ONOFF, false)
end

function OnRecvPacket(packet)
    if packet.header == 0x68 and config.enabled then
        packet.pos = 1
        slot = packet:Decode1()
        packet.pos = 10
        if packet:Decode1() == 0 then
            allChat = true
        else
            allChat = false
        end
        packet.pos = 18
        contentLength = packet:Decode1()
        packet.pos = packet.size - (contentLength+1)
        content = ""
        for i=0, contentLength-1, 1 do
            content = content .. string.char(packet:Decode1())
        end
        if config.german then
            translate(content, slot, allChat, "de")
        elseif config.english then
            translate(content, slot, allChat, "en")
        elseif config.dutch then
            translate(content, slot, allChat, "nl")
        end
    end
end

function urlencode(str)
    if (str) then
        str = string.gsub (str, "\n", "\r\n")
        str = string.gsub (str, "([^%w ])", function (c) return string.format ("%%%02X", string.byte(c)) end)
        str = string.gsub (str, " ", "+")
    end
    return str
end

function translate(text, slot, allChat, language)
    if not DirectoryExist(LIB_PATH.."temp/") then
        CreateDirectory(LIB_PATH.."temp/")
    end
    if FileExist(LIB_PATH.."temp/temp") then
        DeleteFile(LIB_PATH.."temp/temp")
    end
    DownloadFile("http://chepvp.de/translate.php?text="..urlencode(Base64Encode(text)).."&lang="..language, LIB_PATH.."temp/temp", function()
        if FileExist(LIB_PATH.."temp/temp") then
            chat = ReadFile(LIB_PATH.."temp/temp").."</font>"
            prefix = ""
            if chat ~= nil then
                if slot then
                    hero = heroManager:GetHero(slot+1)
                    if hero ~= nil then
                        prefix = hero.name.." ("..hero.charName.."): "
                        if allChat then
                            prefix = "[All] "..prefix
                        end
                    end
                end
                prefix = "<font color='#01B601'>"..prefix.."</font>"
                chat = "<font color='#FFFFFF'>"..chat.."</font>"
                PrintChat(prefix..chat)
            end
            DeleteFile(LIB_PATH.."temp/temp")
        end
    end)
end

function OnTick()
    if config.english then
        config.german = false
        config.dutch = false
    elseif config.german then
        config.english = false
        config.dutch = false
    elseif config.dutch then
        config.german = false
        config.english = false
    end
end