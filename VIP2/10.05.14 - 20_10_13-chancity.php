<?php exit() ?>--by chancity 96.229.9.229
--require "IrcClient"

local function DrawRectangle(x, y, width, height, color)
    DrawLine(x, y + (height/2), x + width, y + (height/2), height, color)
end

function IrcClient__OnLoad()
	connectionSettings = {
	host = "irc01v-bolchat.cloudapp.net",
	port = 6669,
	channel = "#BoLChat",
	nickname = GetUser()
	}
	
	BoLChat = IrcClient(connectionSettings)
	BoLChat:connect()
end

-- Console Configuration
local console = {
    classic = false,
    bgcolor = ARGB( 255, 255, 0, 0 ),
    padding = 2,
    textSize = 16,
    linePadding = 2,
    brand = "BoLChat",
    scrolling = {
        width    = 8
    },
    colors = {
        script = 		{ R =     255, G = 100, B = 0},
		scripterr = 	{ R = 255, 	 G = 255, B = 0 },
        ircme = 		{ R = 255, 	 G = 0, B = 200 },
        ircuser = 		{ R =  0,	 G = 255, B = 225 },
        ircserver = 	{ R =     0, G = 255, B = 0 },
        ircchannel =	{ R =     0, G = 255, B = 0 }
    },
    keys = {
        220, -- German Tilt
        192    -- English Tilt
    },
    selection = {
        content = "",
        startLine = 1,
        endLine = 1,
        startPosition = 1,
        endPosition = 1
    }
}

-- Notifications Configuration
local notifications = {
    bgcolor = ARGB( 170, 0, 0, 0 ),
    max = 1,
    length = 5000,
    fadeTime = 500,
    slideTime = 200,
    perma = 0
}

-- Command line structure
local command = {
    history = {},
    offset = 1,
    methods = {
        -- DEFINED at end of script to allow access to all methods
    }
}



local active = false
local stack  = {}
-- local stack  = {
				-- channel = {
					-- channels = {}}
				-- user = {
					-- username = {}}
				-- server = {}
				-- scripts = {}
				-- errors = {}
				-- }
				
local offset = 1
local closeTick = 0

-- Unorganized variables
local stayAtBottom = true
local maxMessages = math.floor(((WINDOW_H/2) - 2 * console.padding - 2 * console.textSize) / (console.textSize + console.linePadding)) + 1

-- Code ------------------------------------------------
local function IsConsoleKey(key)
    for i, k in ipairs(console.keys) do
        if k == key then
            return true
        end
    end

    return false
end

local function GetTextColor(type, opacity)
    local c = console.colors.default

    if console.colors[type] then
        c = console.colors[type]
    end

    return ARGB((opacity or 1) * 255, c.R, c.G, c.B)
end

function AddMessage(msg, type, insertionOffset)
    msg = msg:gsub("\t", "    "):gsub("<eof>","'eof'"):gsub('<[^>]+>', '')
    local lineNumber = 1
    local length = WINDOW_W/2 - 2 * console.padding - console.scrolling.width - GetTextWidth("[" .. TimerText(GetInGameTimer()) .. "] ")
    for lineNo, line in ipairs(msg:split("\n")) do
        if GetTextWidth(msg) >= length then
            local currentString = ""
            for word in string.gmatch(line, "[^%s]+") do
                local newString = currentString .. (currentString ~= "" and (" " .. word) or word)
                if GetTextWidth(newString) >= length then
                    AddMessageToStack(currentString, type, insertionOffset and (insertionOffset - 1 + lineNumber) or insertionOffset, lineNumber == 1 and GetInGameTimer() or nil)
                    lineNumber = lineNumber + 1

                    currentString = word
                    length = WINDOW_W/2 - 2 * console.padding - console.scrolling.width
                else
                    currentString = newString
                end
            end
            if currentString ~= "" then
                AddMessageToStack(currentString, type, insertionOffset and (insertionOffset - 1 + lineNumber) or insertionOffset, lineNumber == 1 and GetInGameTimer() or nil)
                lineNumber = lineNumber + 1
            end
        else
            AddMessageToStack(line, type, insertionOffset and (insertionOffset - 1 + lineNumber) or insertionOffset, lineNumber == 1 and GetInGameTimer() or nil)
            lineNumber = lineNumber + 1
        end
    end
end

function AddMessageToStack(msg, type, insertionOffset, gameTime)
    if insertionOffset then
        table.insert(stack, insertionOffset, {
            msg = tostring(msg),
            ticks = GetTickCount(),
            gameTime= gameTime,
            type = type
        })
    else
        table.insert(stack, {
            msg = tostring(msg),
            ticks = GetTickCount(),
            gameTime = gameTime,
            type = type
        })
    end

    if #stack - offset >= maxMessages and stayAtBottom then
        offset = offset + 1
    end

    if notifications.perma > 0 then
        for i = 1, notifications.perma do
            if #stack - i >= 1 then
                local item = stack[#stack - i]

                if item.ticks < GetTickCount() - notifications.length + notifications.fadeTime then
                    item.ticks = GetTickCount() - notifications.length + notifications.fadeTime
                    closeTick = GetTickCount() - notifications.length + notifications.fadeTime - 1
                end
            end
        end
    end
end

local function LazyProcess(cmd)
    local preExStack = #stack
    cmd = cmd:trim()
    if cmd:sub(1,1) == "=" then
        local successful, result = ExecuteLUA('return ' .. cmd:sub(2,#cmd))
        if successful then AddMessage(type(result) ~= "userdata" and tostring(result) or "userdata", "command")
        else AddMessage("Lua Error: " .. result:gsub("%[string \"\"%]:1: ", ""), "console") end
    else
        local successful, result = ExecuteLUA(cmd)
        if not successful then
            if not console.classic then
                local successful, result = ExecuteLUA('return ' .. cmd)
                if successful then
                    table.remove(stack, preExStack)
                    AddMessage(cmd .. " = " .. tostring(result), "command", preExStack)
                else AddMessage("Lua Error: " .. result:gsub("%[string \"\"%]:1: ", ""), "console") end
            else AddMessage("Lua Error: " .. result:gsub("%[string \"\"%]:1: ", ""), "console") end
        end
    end
end

function ExecuteLUA(cmd)
    local func, err = load(cmd, "", "t", _ENV)
    if func then
        return pcall(func)
    else
        return false, err
    end
end

local function ProcessCommand(cmd)
    local parts = string.split(cmd, " ", 2)
    if command.methods[parts[1]] == nil then return end
    return command.methods[parts[1]](#parts == 2 and parts[2] or nil)
end

local function ExecuteCommand(cmd)
    if cmd ~= "" then
        AddMessage(cmd, "command")

        if string.len(cmd) == 0 then return end

        -- Display command in console, and add to history stack
        table.insert(command.history, cmd)

        -- Parse the command
        local process = ProcessCommand(cmd)

        -- If no command was found, we will attempt to execute the command as LUA code
        if not process then
            LazyProcess(cmd)
        end
    end
end

function GetTextWidth(text, textSize)
    return GetTextArea("_" .. text .. "_", textSize or console.textSize).x - 2 * GetTextArea("_", textSize or console.textSize).x
end

function Console__WriteConsole(msg)
    AddMessage(msg, "script")
end

function OnTick()
	BoLChat:read()
	
    if BoLChat:messages_size() >= 0 then
		local message = BoLChat:message()
        if message ~= nil then
			if connectionSettings.nickname ~= message.nick then
				if message.cmd == "PRIVMSG" then
					local channel = string.gsub(connectionSettings.channel, "#", "")
					AddMessage(message.nick.."@"..channel..": "..message.message, "ircuser")
				end
			end
        end
    end
 end
 
function OnSendChat(text)
	local CommandTable =	{"/b", "/B"}
	
	for i=1, #CommandTable do
    	if string.find(text, CommandTable[i], 1) then
			local formatedtext = string.gsub(text, CommandTable[i], "")
			local channel = string.gsub(connectionSettings.channel, "#", "")
			
			BoLChat:send("PRIVMSG "..connectionSettings.channel..formatedtext)
			AddMessage(connectionSettings.nickname.."@"..channel..":"..formatedtext, "ircme")

    		BlockChat()
    	end
    end
end

function Console__OnDraw()
    local messageBoxHeight = 2 * console.padding + (maxMessages - 1) * (console.textSize + console.linePadding) + console.textSize
    local consoleHeight        = messageBoxHeight
    local scrollbarHeight    = math.ceil(messageBoxHeight / math.max(#stack / maxMessages, 1))


    if active == true then
        local showRatio = math.min((GetTickCount() - closeTick) / notifications.slideTime, 1)
        local slideOffset = (1 - showRatio) * consoleHeight

        -- Draw console background
        DrawRectangle(0, 0 - slideOffset, WINDOW_W/2, consoleHeight, ARGB(showRatio * 50, 0, 0, 250))
        DrawLine(0, consoleHeight - slideOffset, WINDOW_W/2, consoleHeight - slideOffset, 1, GetTextColor("script", showRatio * 0.58))
		
		-- Draw userlist
		--DrawRectangle(WINDOW_W/2 +5 , 0 - slideOffset, WINDOW_W/2/2, consoleHeight, ARGB(showRatio * 50, 0, 0, 250))
		--DrawLine(WINDOW_W/2 +5, consoleHeight - slideOffset, WINDOW_W/2+5+WINDOW_W/2/2, consoleHeight - slideOffset, 1, GetTextColor("ircuser", showRatio * 0.58))

        -- Display stack of messages
        console.selection.content = ""
        if #stack > 0 then
            for i = offset, offset + maxMessages - 1 do
                if i > #stack then break end

                local message = stack[i]

                local selectionStartLine, selectionEndLine, selectionStartPosition, selectionEndPosition
                if console.selection.startLine < console.selection.endLine or (console.selection.startLine == console.selection.endLine and console.selection.startPosition < console.selection.endPosition) then
                    selectionStartLine = console.selection.startLine
                    selectionEndLine = console.selection.endLine
                    selectionStartPosition = console.selection.startPosition
                    selectionEndPosition = console.selection.endPosition
                else
                    selectionStartLine = console.selection.endLine
                    selectionEndLine = console.selection.startLine
                    selectionStartPosition = console.selection.endPosition
                    selectionEndPosition = console.selection.startPosition
                end

                local timePrefix = message.gameTime and ("[" .. TimerText(message.gameTime) .. "] ") or ""

                if i >= selectionStartLine and i <= selectionEndLine then
                    local rightOffset

                    local leftOffset = (i == selectionStartLine) and (GetTextArea("_" .. (timePrefix .. message.msg):sub(1, selectionStartPosition - 1) .. "_", console.textSize).x - 2 * GetTextArea("_", console.textSize).x) or 0

                    if i == selectionEndLine then
                        local selectedText = (timePrefix .. message.msg):sub(selectionStartLine == selectionEndLine and selectionStartPosition or 1, selectionEndPosition - 1)
                        rightOffset = GetTextWidth(selectedText)

                        console.selection.content = console.selection.content .. (console.selection.content ~= "" and "\r\n" or "") .. selectedText
                    else
                        local selectedText = (timePrefix .. message.msg):sub(selectionStartLine == i and selectionStartPosition or 1)
                        rightOffset = WINDOW_W/2 - 2 * console.padding - leftOffset - (scrollbarHeight == messageBoxHeight and 0 or console.scrolling.width)

                        console.selection.content = console.selection.content .. (console.selection.content ~= "" and "\r\n" or "") .. selectedText
                    end

                    DrawRectangle(console.padding + leftOffset, console.padding + (i - offset) * (console.textSize + console.linePadding) - slideOffset - console.linePadding / 2, rightOffset, console.textSize + console.linePadding, 1157627903)
                end

                if message ~= nil then
                    DrawText(timePrefix .. message.msg, console.textSize, console.padding, console.padding + (i - offset) * (console.textSize + console.linePadding) - slideOffset, GetTextColor(message.type, showRatio))
                end
            end
        end

        DrawText(console.brand, console.textSize, WINDOW_W/2 - GetTextArea(console.brand, console.textSize).x - console.padding, messageBoxHeight + console.padding - slideOffset, GetTextColor("script", showRatio * 0.90))
		--DrawText("Users", console.textSize, WINDOW_W/2+5+WINDOW_W/2/2 - GetTextArea("Users", console.textSize).x - console.padding, messageBoxHeight + console.padding - slideOffset, GetTextColor("ircuser", showRatio * 0.90))

        if scrollbarHeight ~= messageBoxHeight then
            DrawRectangle(WINDOW_W/2 - console.scrolling.width, 0 - slideOffset + (offset - 1) / (#stack - maxMessages) * (messageBoxHeight - scrollbarHeight), console.scrolling.width, scrollbarHeight, GetTextColor("script", showRatio * 0.4))
        end
		
    elseif #stack > 0 then
        local filteredStack = {}

        local notificationsFound = 0
        local currentOffset = #stack
        while notificationsFound ~= notifications.max and currentOffset ~= 0 do
            if (GetTickCount() - stack[currentOffset].ticks > notifications.length or stack[currentOffset].ticks < closeTick) and notificationsFound >= notifications.perma then break end

            if stack[currentOffset].gameTime then
                table.insert(filteredStack, stack[currentOffset])
                notificationsFound = notificationsFound + 1
                currentOffset = currentOffset - 1
            else
                table.insert(filteredStack, stack[currentOffset])
                currentOffset = currentOffset - 1
            end
        end

        if #filteredStack > 0 then
            local slideOffset = 0
            local notificationsFound1 = 0
            for i = 1, #filteredStack do
                slideOffset = slideOffset - (console.textSize + (i == #filteredStack and console.padding * 2 or console.linePadding)) * ((notificationsFound - notificationsFound1 <= notifications.perma) and 0 or math.max((GetTickCount() - filteredStack[#filteredStack - i + 1].ticks - notifications.length + notifications.fadeTime) / notifications.fadeTime, 0))
                if stack[currentOffset].gameTime then
                    notificationsFound1 = notificationsFound1 + 1
                end
            end

            DrawRectangle(0, 0, WINDOW_W/2, (console.textSize * #filteredStack) + (console.padding * 2) + (#filteredStack - 1) * console.linePadding + slideOffset, notifications.bgcolor)
            DrawLine(0, (console.textSize * #filteredStack) + (console.padding * 2) + slideOffset + (#filteredStack - 1) * console.linePadding, WINDOW_W/2, (console.textSize * #filteredStack) + (console.padding * 2) + slideOffset + (#filteredStack - 1) * console.linePadding, 1, GetTextColor("script", 0.27))

            local notificationsFound1 = 0
            for i = 1, #filteredStack do
                local item = filteredStack[#filteredStack + 1 - i]

                local timePrefix = item.gameTime and ("[" .. TimerText(item.gameTime) .. "] ") or ""

                DrawText(timePrefix .. item.msg, console.textSize, console.padding, console.padding + (i - 1) * (console.linePadding + console.textSize) + slideOffset, GetTextColor(item.type, 1 - ((notificationsFound - notificationsFound1 <= notifications.perma) and 0 or math.max((GetTickCount() - item.ticks - notifications.length + notifications.fadeTime) / notifications.fadeTime, 0))) )

                if stack[currentOffset].gameTime then
                    notificationsFound1 = notificationsFound1 + 1
                end
            end
        end
    end
end

function getLineCoordinates(referencePoint)
    local yValue = math.max(math.ceil((referencePoint.y - console.padding - console.textSize) / (console.textSize + console.linePadding)) + 1, 1) + offset - 1
    local xValue = referencePoint.x - console.padding

    if yValue > #stack then
        return #stack + 1, math.huge
    else
        local timePrefix = stack[yValue].gameTime and ("[" .. TimerText(stack[yValue].gameTime) .. "] ") or ""
        local stringValue = timePrefix .. stack[yValue].msg
        local stringWidth = 0
        local charNumber = 0
        for i = 1, #stringValue do
            newStringWidth = stringWidth + GetTextArea("_" .. stringValue:sub(i,i) .. "_", console.textSize).x - 2 * GetTextArea("_", console.textSize).x
            if newStringWidth > xValue then break end
            stringWidth = newStringWidth
            charNumber = i
        end

        return yValue, charNumber + 1
    end
end

function Console__OnMsg(msg, key)
    local messageBoxHeight = 2 * console.padding + (maxMessages - 1) * (console.textSize + console.linePadding) + console.textSize
    local consoleHeight        = messageBoxHeight
    local scrollbarHeight    = math.ceil(messageBoxHeight / math.max(#stack / maxMessages, 1))

    if active and msg == WM_RBUTTONUP and console.selection.content > "" then
		print(console.selection.content)
        SetClipboardText(console.selection.content)
        console.selection = {
            content = "",
            startLine = 1,
            endLine = 1,
            startPosition = 1,
            endPosition = 1
        }
    elseif active and msg == WM_LBUTTONDOWN then
        if GetCursorPos().x >= WINDOW_W/2 - console.scrolling.width then
            dragConsole = true
            dragStart = {x = GetCursorPos().x, y = GetCursorPos().y}
            startOffset = offset
        else
            local line, char = getLineCoordinates(GetCursorPos())

            if line then
                console.selection.startLine = line
                console.selection.endLine = line
                console.selection.startPosition = char
                console.selection.endPosition = char

                selecting = true
            end
        end
    elseif active and msg == WM_LBUTTONUP then
        if selecting then
            local line, char = getLineCoordinates(GetCursorPos())

            if line then
                console.selection.endLine = line
                console.selection.endPosition = char
            end
        end

        dragConsole = false
        selecting = false
    elseif active and msg == WM_MOUSEMOVE then
        if selecting then
            local line, char = getLineCoordinates(GetCursorPos())

            if line then
                console.selection.endLine = line
                console.selection.endPosition = char
            end
        end

        if dragConsole then
            if #stack > maxMessages then
                stayAtBottom = false

                offset = startOffset + math.round(((GetCursorPos().y - dragStart.y) * (#stack - maxMessages) / (messageBoxHeight - scrollbarHeight)) + 1)
                if offset < 1 then
                    offset = 1
                elseif offset >= #stack - maxMessages + 1 then
                    offset = #stack - maxMessages + 1
                    stayAtBottom = true
                end
            end
        end
    end

    if msg == KEY_DOWN and IsConsoleKey(key) then
        active = not active
        closeTick = GetTickCount()
    end
end

-- Console Commands ---------------------------------
command.methods = {
    clear = function()
        stack = {}
        offset = 1
    end,

    dump = function(query)
        local t = ""
        for i, v in ipairs(stack) do
            t = t .. "[" .. TimerText(v.gameTime) .. "] " .. v.msg .. "\n"
        end
        return WriteFile(t, SCRIPT_PATH .. (query~="" and query or "console_dump.log"))
    end
}

function OnUnload()

end

AddLoadCallback(IrcClient__OnLoad)
AddDrawCallback(Console__OnDraw)
AddMsgCallback(Console__OnMsg)

_G.WriteConsole = Console__WriteConsole
_G.PrintChat = _G.WriteConsole
_G.Console__IsOpen = active

class 'IrcClient'
 local socket = require("socket")
 
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
					AddMessage("Welcome to BoLChat, "..self.nickname.."!!! :)", "script")
					AddMessage("Usage: Type '/b' with a messaging following.", "script")
				end
			else
				--print(buffer)
			end
			if buffer ~= nil then
				if string.sub(buffer,1,4) == "PING" then
					IrcClient:send(string.gsub(buffer,"PING","PONG",1))
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
						else
							AddMessage(buffer, "ircserver")
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
