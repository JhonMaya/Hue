<?php exit() ?>--by chancity 96.229.9.229
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
				if message.cmd == "PRIVMSG" then
					local channel = string.gsub(connectionSettings.channel, "#", "")
					print("<font color='#00ff99'>"..message.nick.."@"..channel.."> "..message.message.."</font>")
				elseif message.cmd == "JOIN" or message.cmd == "QUIT" then
					print("<font color='#00ff99'>"..message.nick.." "..message.message.."</font>")
				end
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
			print("<font color='#ff9900'>"..connectionSettings.nickname.."@"..channel..">"..formatedtext.."</font>")

    		BlockChat()
    	end
    end
end

class 'IrcClient'
 local socket = require("socket")
 local io = io
 local string = string
 
 local connection = nil
 local host
 local port
 local channel
 local nickname
 local messages = {ptr = 0, 
				   size = -1}
 
 function IrcClient:__init(settings)
    self.host = settings.host
    self.port = settings.port
    self.nickname = settings.nickname
    self.channel = settings.channel
	
	connection, err = socket.tcp()
	connection:settimeout(0)
	
	if err ~= nil then
		--print(err)
	end
 end

 function IrcClient:connect()
    if connection == nil then
		connection, err = socket.tcp()
		if err ~= nil then
			print(err)
		end
		connection:settimeout(0)
	end
	connection:connect(self.host, self.port)	
 end

function IrcClient:disconnect()
    if connection ~= nil then
        connection:close()
    end
    connection = nil
 end

 function IrcClient:read()
    local buffer, err
    local prefix, cmd, param, param1, param2
    local user, userhost
    err = nil
	
    if connection ~= nil then
        while not err do
            buffer, err = connection:receive("*l")
			if err == nil or err == "timeout" then
				if not connected then
					socket.sleep(1)
					IrcClient:send("NICK "..self.nickname)
					IrcClient:send("USER "..self.nickname.." 0 * :"..self.nickname)
					connected = true
					print("<font color='#ffffff'>Welcome to BoLChat, "..self.nickname.."!!! :)</font>")
					print("<font color='#ffffff'>Usage: Type '/bol' with a messaging following.</font>")
				end
			else
				--print(buffer)
			end
			if buffer ~= nil then
				--print(buffer)
				io.flush()
				if string.sub(buffer,1,4) == "PING" then
					send(string.gsub(buffer,"PING","PONG",1))
				else
					prefix, cmd, param = string.match(buffer, "^:([^ ]+) ([^ ]+)(.*)$")
					if cmd == "376" then
						IrcClient:send("JOIN "..self.channel)	
					end
					if param ~= nil then
						param = string.sub(param,2)
						param1, param2 = string.match(param,"^([^:]+) :(.*)$")
						if cmd == "PRIVMSG" then
							user, userhost = string.match(prefix,"^([^!]+)!(.*)$")	
							messages.size = messages.size + 1
							messages[messages.size] = {}
							messages[messages.size].cmd = cmd
							messages[messages.size].nick = user
							messages[messages.size].message = param2
						elseif cmd == "JOIN" then
							user, userhost = string.match(prefix,"^([^!]+)!(.*)$")
							messages.size = messages.size + 1
							messages[messages.size] = {}
							messages[messages.size].cmd = cmd
							messages[messages.size].nick = user
							messages[messages.size].message = "has joined."								
						elseif cmd == "QUIT" then
							user, userhost = string.match(prefix,"^([^!]+)!(.*)$")	
							messages.size = messages.size + 1
							messages[messages.size] = {}
							messages[messages.size].cmd = cmd
							messages[messages.size].nick = user
							messages[messages.size].message = "has quit."
						end
					end
				end
			end
		end
		return buffer, err
	end		
end	


 function IrcClient:send(data)
       if connection ~= nil then
          connection:send(data.."\r\n")
		  io.flush()
       end
 end
 
 function IrcClient:message()
    local ptr = messages.ptr
    if ptr > messages.size then 
		return nil 
    end
	
    local message = messages[ptr]
    messages[ptr] = nil
    messages.ptr = ptr + 1
    return message
 end
 
  function IrcClient:messages_size()
	return messages.size
 end
