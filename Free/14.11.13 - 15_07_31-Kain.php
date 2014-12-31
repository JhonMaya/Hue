<?php exit() ?>--by Kain 97.90.203.108
--[[
AuthRequest = {
	dev = "Kain",
	sid = "1", 
	sver = "143",
	key = "590ef45081b467c801fda1dd617bae9044be814c66ee092c90580683ff28de7d",
	debug_mode = "true",
	debug_raw_data = "true",
	authfailmode = "kill"
}
--]]
--[[
BoLScriptsAPI = {
	Auth = {
		Status = { Inactive = 0, Active = 1, Pending = 2, Banned = 3, Restricted = 4 }
		FailMode = { kill = 0, advanced = 1 }
	}
}

if BoLScriptsAPI.Auth.Status = "banned" then
	if BoLScriptsAPI.Auth.FailMode = "kill" then
		os.exit()
	end
end
--]]
local apiBootLoaderVersion = "1"
local authResult = false
local fileStarted = false
local fileDownloaded = false
local scriptLoaded = false
local tick = nil

function getFieldCode(field)
	local code = nil

	if field == "action" then
		code = 1
	elseif field == "bver" then
		code = 2
	elseif field == "dev" then
		code = 3
	elseif field == "un" then
		code = 4
	elseif field == "sid" then
		code = 5
	elseif field == "sver" then
		code = 7
	elseif field == "hwid" then
		code = 9
	elseif field == "key" then
		code = 8
	elseif field == "cname" then
		code = 10
	elseif field == "cuname" then
		code = 11
	elseif field == "uuid" then
		code = 12
	elseif field == "v" then
		code = 13
	elseif field == "time" then
		code = 14
	elseif field == "stream" then
		code = 15
	elseif field == "q" then
		code = 20
	elseif field == "result" then
		code = 25
	end

	return code
end

function encryptString(str, field)
	local code = getFieldCode(field)

	-- character table
    local chars="1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM-=&."
	strlen = string.len(chars);

	-- new code begin
    local newcode=""
	-- we start
    for i=1, 999 do
        if string.sub(str,i,i) == "" then
            break
        else
			com=string.sub(str,i,i)
		end
        for x=1, strlen do
			cur=string.sub(chars,x,x)
			if com == cur then
				new=x+code
				while new > strlen do
					new = new - strlen
				end
				newcode=""..newcode..""..string.sub(chars,new,new)..""
			end
		end
    end
    return newcode
end

function decryptString(str, field)
	local code = getFieldCode(field)

	-- character table
    local chars="1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM-=&."
	strlen = string.len(chars);

	-- new code begin
   local newcode=""
	-- we start
    for i=1, 999 do
        if string.sub(str,i,i) == "" then
            break
        else
			com=string.sub(str,i,i)
		end
        for x=1, strlen do
			cur=string.sub(chars,x,x)
			if com == cur then
				new=x-code
				while new < 0 do
					new = new + strlen
				end
				newcode=""..newcode..""..string.sub(chars,new,new)..""
			end
		end
    end
    return newcode
end

function split(pString, pPattern)
    local Table = {}
    local fpat = "(.-)" .. pPattern
    local last_end = 1
    local s, e, cap = pString:find(fpat, 1)
    while s do
        if s ~= 1 or cap ~= "" then
            table.insert(Table,cap)
        end
        last_end = e+1
        s, e, cap = pString:find(fpat, last_end)
    end
    if last_end <= #pString then
        cap = pString:sub(last_end)
        table.insert(Table, cap)
    end
    return Table
end

-- Encoding
function enc(data)
	local b='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'

    return ((data:gsub('.', function(x) 
        local r,b='',x:byte()
        for i=8,1,-1 do r=r..(b%2^i-b%2^(i-1)>0 and '1' or '0') end
        return r;
    end)..'0000'):gsub('%d%d%d?%d?%d?%d?', function(x)
        if (#x < 6) then return '' end
        local c=0
        for i=1,6 do c=c+(x:sub(i,i)=='1' and 2^(6-i) or 0) end
        return b:sub(c+1,c+1)
    end)..({ '', '==', '=' })[#data%3+1])
end

-- Decoding
function dec(data)
	local b='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'

    data = string.gsub(data, '[^'..b..'=]', '')
    return (data:gsub('.', function(x)
        if (x == '=') then return '' end
        local r,f='',(b:find(x)-1)
        for i=6,1,-1 do r=r..(f%2^i-f%2^(i-1)>0 and '1' or '0') end
        return r;
    end):gsub('%d%d%d?%d?%d?%d?%d?%d?', function(x)
        if (#x ~= 8) then return '' end
        local c=0
        for i=1,8 do c=c+(x:sub(i,i)=='1' and 2^(8-i) or 0) end
        return string.char(c)
    end))
end

function readAll(file)
    local f = io.open(file, "r")
    local content = f:read("*all")
    f:close()

    return content
end

function readFirstLine(file)
    local f = io.open(file, "r")
    local content = f:read("*line")
    f:close()

    return content
end

function DownloadComplete()
	if FileExist(filePath) then
		local code = readAll(filePath)
		os.remove(filePath)
		if code then
			fileCode = code
			ShowAuthMessage("<font color='#000000'>".."!"..": </font>".."<font color='#FF0000'> Updated, Reload script for new version.</font>", false)
			fileDownloaded = true
		else
			ShowAuthMessage("<font color='#000000'>".."!"..": </font>".."<font color='#FF0000'>There was a problem dynamically downloading script contents.</font>", true)
		end
	end
end

function LoadScriptIntoMemory(code)
	if code then
		E = load(code, "RandomName", nil, _G)
		E()
		scriptLoaded = true
		return true
	end
end

function DownloadScript(url, savePath)
	RunCmdCommand("%WINDIR%/System32/bitsadmin.exe /transfer 'bol' "..url.." "..string.gsub(savePath, "/", "\\"))
end

function LoadScript(url)
	local randomNumber = math.random(1000, 9999)
	local tempFile = "jfsdhfg"..randomNumber..".txt"
	local tempFilePath = os.getenv("APPDATA")..tempFile

	DownloadScript(url, tempFilePath)
	if FileExist(tempFilePath) then
		if not AuthRequest["download_type"] or AuthRequest["download_type"] == "stream" then
			local code = readAll(tempFilePath)
			os.remove(tempFilePath)
			if code then
				LoadScriptIntoMemory(code)
				code = nil
				ShowAuthMessage("Script was Loaded from web.", false)
				fileDownloaded = true
			else
				ShowAuthMessage("There was a problem dynamically downloading script contents.", true)
			end
		elseif AuthRequest["download_type"] == "download" and AuthRequest["script_path"] and AuthRequest["sver"] then
			local firstLine = readFirstLine(tempFilePath)
			if not firstLine:find("-- !!EMPTY!!") then
				os.remove(AuthRequest["script_path"])
				os.rename(tempFilePath, AuthRequest["script_path"])
				ShowAuthMessage("Script was updated. Please double F9 to reload.", true)
				-- os.exit()
			else
				-- Empty result. No update available.
				ShowAuthMessage("You are already running the latest script version.", false)
			end
		end
	end
end

function UpdateBootLoader(currentVersion, latestVersion)
	if currentVersion and latestVersion and latestVersion ~= "0" and currentVersion ~= latestVersion then
		local file = "BoLScripts.lua"
		local filePath = SCRIPT_PATH.."\\Common\\"..file

		if FileExist(filePath) then
			os.remove(filePath)
		end

		local scripturl = "http://bolscripts.com/account/files/BoLScripts.lua";
		DownloadScript(scripturl, filePath)

		return true
	else
		return false
	end
end

function UpdateScript(url)
	if AuthRequest["script_path"] and AuthRequest["sver"] then

	end

end

function LoadScriptOld(url)
	fileStarted = true
	DownloadFile(url, filePath, DownloadComplete)
end

-- Execute a command.
-- The first return value is the output from the command (or nil if there was no output).
-- The second is the returned status code from executing the command.
function MyExecute(Command)
   -- We use os.tmpname(), a built-in Lua function, to give us a temporary file name.
   local TempFileName = os.tmpname()
   local Result = os.execute(Command..' > '..TempFileName..' 2>&1')
   local TempFile = io.open(TempFileName)
   if TempFile then
      local CommandOutput = TempFile:read('*a')
      TempFile:close()
      os.remove(TempFileName)
      return CommandOutput, Result
   else
      return nil, Result
   end
end

function FinishAuthCheck(result)
	if not result then
		return false
	end

	if result then
		result = dec(result)

		local AuthResponseSplit = split(result, ";")
		if #AuthResponseSplit < 1 then
			ShowAuthMessage("There was an unknown error.", true)
			return
		elseif #AuthResponseSplit <= 2 then
			if AuthResponseSplit[1]:lower():find("404 not found") then
				ShowAuthMessage("404 Auth server not found. Please inform Admin.", true)
				return
			-- elseif 
			else
				ShowAuthMessage(AuthResponseSplit[1], true)
			end
			return
		elseif #AuthResponseSplit == 2 then

		elseif #AuthResponseSplit < 7 then
			ShowAuthMessage("Authorization request was improperly formed.", true)
		end

		AuthResponse = {}
		for _, item in pairs(AuthResponseSplit) do
			local itemSplit = split(item, ",")
			AuthResponse[itemSplit[1]] = itemSplit[2];
		end

		--[[
			AuthResponse:
				UserStatus
				UserMsg
				ScriptName
				ScriptDev
				ScriptStatus
				Message
				LatestVersion
				APILatestVersion
				ScriptURL
		--]]

		if AuthRequest["debug_mode"] == "true" and AuthRequest["debug_raw_data"] == "true" then
			ShowAuthMessage("raw: "..result)
		end

		statusCode = {
			ok = "1"
		}

		-- Update Boot Loader if needed.
		-- UpdateBootLoader(apiBootLoaderVersion, AuthResponse["APILatestVersion"])

		if AuthResponse["UserStatus"] ~= statusCode["ok"] then
			-- Invalid user.
			if AuthResponse["UserMsg"] ~= "" then
				ShowAuthMessage(AuthResponse["UserMsg"], true)
			else
				ShowAuthMessage("User authorization error.", true)
			end
		elseif AuthResponse["ScriptStatus"] ~= statusCode["ok"] then
			-- Invalid script.
			if AuthResponse["ScriptStatus"] ~= "" then
				ShowAuthMessage(AuthResponse["ScriptMsg"], true)
			else
				ShowAuthMessage("Script authorization error.", true)
			end
		elseif AuthResponse["UserStatus"] == statusCode["ok"] and AuthResponse["ScriptStatus"] == statusCode["ok"] then
			-- Validated Successfully.
			local scriptName = AuthResponse["ScriptName"]
			if scriptName == "" then
				scriptName = "Script"
			end

			local devName = ""
			if AuthResponse["ScriptDev"] == "" then
				devName = ""
			else
				devName = " by "..AuthResponse["ScriptDev"]
			end

			ShowAuthMessage("Welcome "..GetUser()..". <font color='#FE2EF7'>'"..scriptName.."'"..devName.." has been activated.</font> <font color='#FFFF00'>Enjoy!</font>", false)
			authResult = true
		end
		
		if AuthResponse["DownloadType"] and AuthResponse["ScriptURL"] and AuthResponse["DownloadType"] == "2" then
			-- PrintChat(""..AuthResponse["ScriptURL"])
		end

		if AuthResponse["Message"] then
			ShowAuthMessage(AuthResponse["Message"])
		end
	else
		print("<font color='#c22e13'>Failed Auth Check.</font>")
	end
end

function ShowAuthMessage(message, statusError)
	local prefix = "<u><b><font color='#2E9AFE'>BoL</font></b><font color='#00BFFF'><b><i>Scripts</i></b>.com</font></u><font color='#2E9AFE'>:</font> "

	if statusError then
		PrintChat(prefix.."<font color='#c22e13'>"..message.."</font>")
	else
		PrintChat(prefix.."<font color='#00FF40'>"..message.."</font>")
	end
end

function _G.AuthCheck()
	return authResult
end

function _G.FileStarted()
	return fileStarted
end

function _G.FileDownloaded()
	return fileDownloaded
end

function _G.ScriptLoaded()
	return scriptLoaded
end

function trim(s)
	return (string.gsub(s, "^%s*(.-)%s*$", "%1"))
end

function url_encode(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
        function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str	
end

function GetUUID()
	local randomNumber = math.random(1000, 9999)
	local tempFile = "jfsdhf"..randomNumber..".txt"
	local tempFilePath = os.getenv("APPDATA").."/"..tempFile -- os.getenv("APPDATA")
	RunCmdCommand("wmic csproduct get UUID | findstr /IV uuid > "..tempFilePath)
	local lines = ""
	local fp = io.open(tostring(tempFilePath), "r" )
	for line in fp:lines() do
		lines = lines .. line
	end
	fp:close()
	-- RunCmdCommand("rm /F /Q "..tempFilePath)
	os.remove(tempFilePath)
	return trim(lines)
end

function Unload()
	ClearTable(AuthRequest)
	ClearTable(AuthRequestExtras)
end

function GetScriptFilePath()
	local debugInfo = debug.getinfo(BootLoader)
	if debugInfo and debugInfo["source"] then
		return string.gsub(debugInfo["source"], "@", "")
	end

	return nil
end

function ClearTable(tableArray)
	for key, value in pairs(tableArray) do
		tableArray[key] = nil
	end
end

function PrintTable(tableArray)
	PrintChat("Table Contents:")
	for key, value in pairs(tableArray) do
		PrintChat(key.."="..value)
	end
end

AuthRequestExtras = {
	apiver = "1",
	hwid = tostring(os.getenv("PROCESSOR_IDENTIFIER") .. "|" .. os.getenv("PROCESSOR_LEVEL") .. "|" .. os.getenv("PROCESSOR_REVISION")),
	computerUser = os.getenv("USERNAME"),
	computerName = os.getenv("COMPUTERNAME"),
}

if AuthRequest then
	ShowAuthMessage("<font color='#c22e13'>Waiting for auth response...</font>", false)
	local timeStr = os.date("!%x %X", os.time())
	queryString = "dev="..encryptString(AuthRequest["dev"], "dev").."&sid="..encryptString(AuthRequest["sid"], "sid").."&sver="..encryptString(AuthRequest["sver"], "sver").."&hwid="..encryptString(AuthRequestExtras["hwid"], "hwid").."&un="..encryptString(GetUser(), "un").."&key="..encryptString(AuthRequest["key"], "key").."&cname="..encryptString(AuthRequestExtras["computerName"], "cname").."&cuname="..encryptString(AuthRequestExtras["computerUser"], "cuname").."&uuid="..encryptString(GetUUID(), "uuid").."&time="..encryptString(timeStr, "time").."&v="..encryptString(AuthRequestExtras["apiver"], "v").."&bver="..encryptString(apiBootLoaderVersion, "bver")

	queryStringAuth = "action="..encryptString("auth", "action").."&"..queryString
	queryStringEnc = enc(encryptString(queryStringAuth, "q"))

	if AuthRequest["debug_mode"] == "true" then
		ShowAuthMessage("queryString: q="..queryStringAuth, false)
		if AuthRequest["debug_raw_data"] == "true" then
			ShowAuthMessage("queryStringEnc: q="..url_encode(queryStringEnc), false)
		end
	end

	local queryURL = "api/api.php?q="..url_encode(queryStringEnc)
	GetAsyncWebResult("bolscripts.com", queryURL, FinishAuthCheck)

	-- Load Script From Web
	downloadType = ((not AuthRequest["download_type"] or AuthRequest["download_type"] == "stream") and "1" or "0")
	queryStringGetScript = "action="..encryptString("getscript", "action").."&"..queryString.."&stream="..encryptString(downloadType, "stream")
	queryStringEnc = enc(encryptString(queryStringGetScript, "q"))

	scripturl = "http://bolscripts.com/api/api.php?q="..url_encode(queryStringEnc)
	LoadScript(scripturl)
	Unload()
end

function BootLoader()
--	if not AuthCheck() then return end
end

BootLoader()
-- AddLoadCallback(BootLoader)
