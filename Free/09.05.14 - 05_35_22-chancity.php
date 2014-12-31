<?php exit() ?>--by chancity 96.229.9.229
require "IrcClient"


function OnLoad()
	connectionSettings = {
	host = "irc01v-bolchat.cloudapp.net",
	port = 6669,
	channel = "#BoLChat",
	nickname = GetUser()
	}
	
	BoLChat = IrcClient(connectionSettings)
	BoLChat:connect()
end

 function OnTick()
	BoLChat:read()
	
    if BoLChat:messages_size() > 0 then
		local message = BoLChat:message()
        if message ~= nil then
			if connectionSettings.nickname ~= message.nick then
				local channel = string.gsub(connectionSettings.channel, "#", "")
				print("<font color='#00ff99'>"..message.nick.."@"..channel..": "..message.message.."</font>")
			end
        end
    end
 end
 
function OnSendChat(text)
	local CommandTable =	{"/bol", "/Bol", "/BOL"}
	
	for i=1, #CommandTable do
    	if string.find(text, CommandTable[i], 1) then
			local formatedtext = string.gsub(text, CommandTable[i], "")
			local channel = string.gsub(connectionSettings.channel, "#", "")
			
			BoLChat:send("PRIVMSG "..connectionSettings.channel..formatedtext)
			print("<font color='#ff9900'>"..connectionSettings.nickname.."@"..channel..":"..formatedtext.."</font>")

    		BlockChat()
    	end
    end
end