<?php exit() ?>--by kevinkev 27.253.95.57


function explode(d,s)
    if (d=='') then return false end
    local p,ar = 0,{}
    for st,sp in function() return string.find(s,d,p,true) end do
        table.insert(ar,tonumber(string.sub(s,p,st-1)))
        p = sp + 1
    end
    table.insert(ar,tonumber(string.sub(s,p)))
    return ar
end
function cer(data)
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
function dl_p(m)
    print("<font color='#FFFFFF'>"..m.."</font>")
end
function dl_e(m)
    print("<font color='#FF0000'>"..m.."</font>")
end
function OnLoad()
	if loaded then return end
    local ts = cer(tostring(os.date("%S%m%d%M"))..tostring(GetTickCount()))
    
    local STANDARD = cer("."..ts)
    local PROFESSIONAL = cer(".."..ts)
    local INVALID = cer(","..ts)
    local hwid = tostring(os.getenv("USERNAME") .. ",," .. os.getenv("PROCESSOR_LEVEL") .. ",," .. os.getenv("PROCESSOR_REVISION")) .. ",," .. os.getenv("PROCESSOR_IDENTIFIER") .. ",," ..ts .. ",," ..tostring(GetUser())
    
    local webRootPATH = "lazygamerz.org"
    
    local s = GetWebResult(webRootPATH,"kevinkev/validate.php","t="..tostring(cer(hwid)))
    if string.find(s,STANDARD) then 
        dl_p("STANDARD license verified.") 
    elseif string.find(s,PROFESSIONAL) then 
        dl_p("PRO license verified.")
    elseif string.find(s,INVALID) then
        dl_e("INVALID license detected.")
        return
    else
        dl_e(s)
        dl_e(STANDARD)
        dl_e(PROFESSIONAL)
        dl_e(INVALID)
        return
    end
    
--REMOVE LOCAL FILES THAT AREN'T MEANT TO BE SAVED
	
--HWID CHECK (server side, but add one for local)
	PrintChat("<font color='#6666FF'>Connected to server...</font>")
--LOCAL VARIABLES
    local webFolder
    if GetUser() == "kevinkev" or GetUser() == "Kevinkev" then 
        webFolder = "C:/xampp/htdocs/KevinBot-1.07c/"
    else
        webFolder = "http://"..webRootPATH.."/kevinkev/KevinBot-1.07c/"
    end
	local KBOT_PATH = SCRIPT_PATH.."/KevinBot/"
	action = "Download".."File"
	local sf = string.format
--LOCAL FUNCTIONS (irregardless of time/space & changes to AllClass.lua)
	function fe(path)
		local file = io.open(tostring(path), "r")
		if file then file:close() return true else return false end
	end
	function needUpdate(filePath,fileSize)
		local file = assert(io.open(filePath, "rb"))
		local size=file:seek("end")
        if (tonumber(size) ~= tonumber(fileSize)) then
            return true
        end
	end
	function r_s()
		return "?&id="..tostring(math.random())..tostring(os.clock())
	end
	function fs(path)
		if fe(path) then
			local f = assert(io.open(path, "r"))
			local t = f:read("*all")
			f:close()
			return t
		end
	end
	function deleteFile(path)
		local filename = {string.match(path:gsub("/","\\"), "(.-)([^\\/]-%.?([^%.\\/]*))$")}
		RunCmdCommand("del /F "..'"'..tostring(filename[1]..'.kbot_0*"')) 
	end
    function hideFile(path)
        RunCmdCommand("attrib +h "..'"'..path:gsub("/","\\")..'"') 
    end
--FOLDERS CREATION CHECK
	--KEVINBOT
	--bLogs
	--Screenshots
	--logs
	--local p = package.path:gsub("?.lua", ""):gsub("/", "\\")
	RunCmdCommand('mkdir "' ..KBOT_PATH..'\\"')
	RunCmdCommand('mkdir "' ..KBOT_PATH..'\\Screenshots\\"')
	RunCmdCommand('mkdir "' ..KBOT_PATH..'\\logs\\"')
	RunCmdCommand('mkdir "' ..KBOT_PATH..'\\bLogs\\"')
	
--UPDATE LINKS
	
--DOWNLOAD ARRAY
    local changelog_file = "changelog.txt"
	local exe_file = "KevinBot.exe"
    --[[
	local change_size = GetWebResult(webRootPATH,"kevinkev/KevinBot-1.07c/sizes.php","f="..changelog_file)
    local exe_size = GetWebResult(webRootPATH,"kevinkev/KevinBot-1.07c/sizes.php","f="..exe_file)
    local bot_size = GetWebResult(webRootPATH,"kevinkev/KevinBot-1.07c/sizes.php","f=botLogicWITHhwid.lua")
    local supported_size = GetWebResult(webRootPATH,"kevinkev/KevinBot-1.07c/sizes.php","f=supportedChampionsList.txt")
    ]]
	file_db = {
	--DESC, 									LINK, 										FILENAME(path), 					CALLBACK, 	HIDDEN (FLAG),	SIZE (update if changed)
	{"KevinBot latest version"				,	webFolder .. "KevinBot.exe"				,	KBOT_PATH 	.. exe_file					        , "notify"	,false			,	exe_size or false    },
	{"Changelog"							,	webFolder .. "changelog.txt"			,	KBOT_PATH 	.. changelog_file					, "notify"	,false			,	change_size or false    },
	{".dll files"							,	"http://puu.sh/3lu5n/2db685810c.dll"	,	KBOT_PATH 	.. "lua51.dll"						, "notify"	,true			,	false		},
	{"Bot handler"							,	"http://puu.sh/3lurk/3dc57c71e3.handle"	,	KBOT_PATH 	.. "bot.handle"						, "notify"	,true			,	false		},
	{"Queue logic"							,	webFolder .. "botLogicWITHhwid.lua"		,	KBOT_PATH 	.. ".bot"							, "notify"	,true			,	bot_size or false	},
	{"Supported champions list"				,	webFolder .. "supportedChampionsList.txt",	KBOT_PATH 	.. "Champions List.txt"				, "notify"	,false			,	supported_size or false	},
	{"Default KBot Configuration file"		,	webFolder .. "script.lua"				,	KBOT_PATH 	.. "script.lua"						, "notify"	,false			,	false	},
	{"Dominion Bot latest version"			,	webFolder .. "DominionItems.lua",           SCRIPT_PATH .. ".kbot_"..tostring(math.random()), "play"	,false			,	1		},
	} --webFolder .. "DominionItems.lua"

	local fname
	for i,url in ipairs(file_db) do
		--Local variables
		local filename = {string.match(url[3]:gsub("/","\\"), "(.-)([^\\/]-%.?([^%.\\/]*))$")}
		local fname = "a" .. tostring(i)
		--FUNCTION CALLBACKS
		if url[4] == "play" then
			local function fileCorrupt()
				PrintChat("<font color='#FF0000'>File corrupt, please redownload</font>")
			end
			
			--[[ DOMINION ]]--
			_ENV[fname] = function() 
				PrintChat(sf("<font color='#FFFFFF'>[DL]</font><font color='#FFFF00'><b>%-15s %-s</b></font>","<font color='#FFFFFF'>->successful</font>",url[1]))
				PrintChat("<font color='#00FFFF'>LOADING...</font>")
				if _G.C_UNIT_INVISIBLE == true or _G.C_UNIT_INVISIBLE == "corrupt" then 
					fileCorrupt() 
					deleteFile(url[3])
					return
				end
				_G.C_UNIT_INVISIBLE = true
				
				LoadScript(filename[2])
				deleteFile(url[3])
				
				_G.C_UNIT_INVISIBLE = nil
				
				
			end
		else
			_ENV[fname] = function() 
				PrintChat(sf("<font color='#FFFFFF'>[DL]</font><font color='#FFFF00'><b>%-15s %-s</b></font>","<font color='#FFFFFF'>->successful</font>",url[1]))
				if url[5] then hideFile(url[3]) end
			end
		end
		--DOWNLOAD ACTION
		--If file exists, check if update needed (unless not updatable)--
		if fe(url[3]) and url[6] ~= nil then
			if needUpdate(url[3],url[6]) and not url[6] == false then
				_G[action](url[2]..r_s(),url[3],_ENV[fname])
			else
				PrintChat(sf("<font color='#777777'>[DL]->Already Updated </font><font color='#FFFF00'>%s</font>",url[1]))
			end
		else
		
		_G[action](url[2]..r_s(),url[3],_ENV[fname])
		end
		
	end
	
--HELPER METHODS
	
end
if not loaded then OnLoad() loaded = true end
if 1==1 then return end