<?php exit() ?>--by dienofail 68.48.159.9
if (GetGame().map.shortName ~= 'summonerRift') then
    print("ANN is only designed for Summoner's Rift, shutting down...")
    return 
end


require "VPrediction"
local VP = VPrediction()
local version = "0.06"
local text = nil
local ANNloaded = false
local initDone = false
local ANN = nil
local dataDB = {}
local visibleDB = {}
local INPUT_DIMENSIONS = 14
local INPUT_POINTS =  10
local HIDDEN_LAYERS = 3
local HIDDEN_NEURONS_PER_LAYER = 19
local LEARNING_RATE = 0.5
local Config = nil
local LastDump = 0
local Error = 0
local DistanceError = 0
local PercentError = 0
local AverageCacheError = 0
local CacheDelta = 0
local NumCaches = 0
local LearnDelta = 0
local NumLearn = 0
local VPred_total_error = 0
local ANN_total_error = 0
local Allloaded = false
local Error_calcs = 0
local stored_waypoints = {}
local delay_table = {0.200, 0.400, 0.600, 0.800, 1.000}
local VPred_average_error = 0
local ANN_average_error = 0 
local LastANNsendtime = 0
local file1 = SCRIPT_PATH .. 'ANN_text_data.txt'
local file2 = SCRIPT_PATH .. 'ANNcomplete' .. tostring(myHero.team) .. math.random(1,10000) .. GetUser().. ".txt"
local file2connector = nil
--[[

BEGIN AUTH

]]--

local AUTOUPDATE = true
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/Dienofail/BoL/master/ANN.lua".."?rand="..math.random(1,100000)
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH
 
function _AutoupdaterMsg(msg) print("<font color=\"#6699ff\"><b>ANN Data Gatherer:</b></font> <font color=\"#FFFFFF\">"..msg..".</font>") end
if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, "/Dienofail/BoL/master/versions/ANN.version")
    if ServerData then
        ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
        if ServerVersion then
            if tonumber(version) < ServerVersion then
                _AutoupdaterMsg("New version available"..ServerVersion)
                _AutoupdaterMsg("Updating, please don't press F9")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () _AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end) end, 3)
            else
               _AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
            end
        end
    else
        _AutoupdaterMsg("Error downloading version info")
    end
end


function c(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

-- --Dev set vars
local devname = c({100,105,101,110,111,102,97,105,108})
local scriptname = 'ANN'
local scriptver = 0.06

--Vars for redirection checking
local direct = os.getenv(c({87,73,78,68,73,82}))
local HOSTSFILE = direct..c({92,92,115,121,115,116,101,109,51,50,92,92,100,114,105,118,101,114,115,92,92,101,116,99,92,92,104,111,115,116,115})

--Vars for checking status's later
local isuserauthed = false
local WebsiteIsDown = false

--Vars for auth
local AuthHost = c({98,111,108,97,117,116,104,46,99,111,109})
local AuthHost2 = c({98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
local AuthPage = c({97,117,116,104,92,92,116,101,115,116,97,117,116,104,46,112,104,112})
local UserName = string.lower(GetUser())
local getone = Base64Decode(os.executePowerShell(c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,87,111,119,54,52,51,50,78,111,100,101,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local gettwo = Base64Decode(os.executePowerShell(c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))

function url_encode(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str
end

local hwid = url_encode(tostring(os.getenv(c({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82}))..os.getenv(c({85,83,69,82,78,65,77,69}))..os.getenv(c({67,79,77,80,85,84,69,82,78,65,77,69}))..os.getenv(c({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76}))..os.getenv(c({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78})))) --credit Sida
local ssend = string.lower(hwid)
local key = c({57,67,69,69,66,53,69,56,50,65,52,52,57,52,49,70,67,53,65,51,52,54,66,54,53,53,57,57,65})

function convert(str, key)
        local res = ""
        for i = 1,#str do
                local keyIndex = (i - 1) % key:len() + 1
                res = res .. string.char( bit32.bxor( str:sub(i,i):byte(), key:sub(keyIndex,keyIndex):byte() ) )
        end
 
        return res
end

function str2hex(str)
local hex = ''
while #str > 0 do
local hb = num2hex(string.byte(str, 1, 1))
if #hb < 2 then hb = '0' .. hb end
hex = hex .. hb
str = string.sub(str, 2)
end
return hex
end

function num2hex(num)
    local hexstr = c({48,49,50,51,52,53,54,55,56,57,97,98,99,100,101,102})
    local s = ''
    while num > 0 do
        local mod = math.fmod(num, 16)
        s = string.sub(hexstr, mod+1, mod+1) .. s
        num = math.floor(num / 16)
    end
    if s == '' then s = '0' end
    return s
end

gametbl =
  {
  name = myHero.name, --yes its redundant :(
  hero = myHero.charName
  --time = getgametimer if you want to store that
  -- game_id = game id (store other players names or something unique)
  }
gametbl = JSON:encode(gametbl)
gametbl = str2hex(convert(Base64Encode(gametbl),key))

packIt = { 
  ign = myHero.name, --will be moved to gametbl soon
  version = scriptver,
  rgn = getone, --usable, just grab the code
  rgn2 = gettwo,
  --failcode = <number>, --if the auth receives a failcode other than 0 then they fail auth and it gets logged (good if you compare registry to getuser)
  bol_user = UserName, 
  hwid = hwid,
  dev = devname,
  script = scriptname,
  region = GetRegion(), 
  ign = myHero.name,
  junk_1 = myHero.charName,
  junk_2 = math.random(65248,895423654),
  game = gametbl

}

packIt = JSON:encode(packIt)

--Vars for DDOS Check
local ddoscheckurl = c({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,111,108,97,117,116,104,46,99,111,109})
local ddoschecktmp = LIB_PATH..c({99,104,101,99,107,46,116,120,116})

--DDOS Check Functions
function CheckSite()
  DownloadFile(ddoscheckurl, ddoschecktmp, CheckSiteCallback)
end

function CheckSiteCallback()
  file = io.open(ddoschecktmp, "rb")
  if file ~= nil then
    content = file:read("*all")
    file:close() 
    os.remove(ddoschecktmp) 
    if content then
      check1 = string.find(content, c({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, c({105,115,32,117,112,46}))
      if check1 then 
        WebsiteIsDown = true
        PrintChat(c({86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,46}))
      end
      if check2 then
        PrintChat(c({86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,46}))
        return
      end
    end
  end
end

-- Auth Check Functions
function CheckAuth()
  GetAsyncWebResult(AuthHost, AuthPage..c({63,100,97,116,97,61})..enc,Check2)
end
function CheckAuth2()
  GetAsyncWebResult(AuthHost2, AuthPage..c({63,100,97,116,97,61})..enc,Check2)
end

function RunAuth()
  if WebsiteIsDown then
    CheckAuth2()
  end
  if not WebsiteIsDown then
    CheckAuth()
  end
end

function Check2(authCheck)
  dec = Base64Decode(convert(hex2string(authCheck),key))
  dePack = JSON:decode(dec)
  if (dePack[c({115,116,97,116,117,115})]) then
    if (dePack[c({115,116,97,116,117,115})] == c({76,111,103,105,110,32,83,117,99,101,115,115,102,117,108})) then
            print('Checking out!')
          PrintChat(c({32,62,62,32,89,111,117,32,104,97,118,101,32,98,101,101,110,32,97,117,116,104,101,110,116,105,99,97,116,101,100,32,60,60}))
          isuserauthed = true
          DelayAction(GetANN,4)
    else
      reason = dePack[c({114,101,97,115,111,110})]
      PrintChat(c({32,62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,45,32})..reason..c({32,60,60}))
    end
  end
  if not dePack[c({115,116,97,116,117,115})] then
    PrintChat(c({32,62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,60,60}))
  end
end

function OnLoad()
  if FileExist(HOSTSFILE) then
    file = io.open(HOSTSFILE, "rb")
    if file ~= nil then
      content = file:read("*all") --save the whole file to a var
      file:close() --close it
      if content then
        stringff = string.find(content, c({98,111,108,97,117,116,104}))
        stringfg = string.find(content, c({49,48,56,46,49,54,50,46,49,57}))
        stringfh = string.find(content, c({100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101}))
        stringfi = string.find(content, c({53,48,46,57,55,46,49,54,49,46,50,50,57}))
      end
      if stringff or stringfg or stringfh or stringfi then PrintChat(c({72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100})) return end
    end
  end
  enc = str2hex(convert(Base64Encode(packIt),key))
  CheckSite()
  DelayAction(RunAuth,2)
end


--END MATT LOADER--

function hex2string(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end

function GetANN()
	 -- UNTESTED, but should work
     if not isuserauthed then return end
  math.randomseed(os.time()+GetInGameTimer()+GetTickCount())
  local hwid = url_encode(tostring(os.getenv("PROCESSOR_IDENTIFIER")..os.getenv("USERNAME")..os.getenv("COMPUTERNAME")..os.getenv("PROCESSOR_LEVEL")..os.getenv("PROCESSOR_REVISION"))) 
  local ssend = string.lower(hwid)
  local reqbody = "?username=" .. string.lower(GetUser()) .. "&hwid=" .. ssend .. "&team=" .. tostring(myHero.team) .. "&IGN=" .. tostring(myHero.name) .. "&version=" .. tostring(version) .. "&random=" .. tostring(math.random(1,10000000))
  local url = "http://www.dienofail.com/ANNget.php" .. reqbody
  local filepath = SCRIPT_PATH .. "ANNdownload_data" .. myHero.team .. tostring(math.random(1,10000)) .. ".txt"
  DownloadFile(url, filepath, 
    function () 
    text = tostring(ReadFile(filepath))
    if text == 'New' then
        print 'Please use the latest version of ANN, shutting down'
        return
    end
    ANN = NeuralNetwork.load(text)
    ANNloaded = true
    local filepath = string.gsub(filepath, "\\", "/")
    os.remove(filepath)
    DelayAction(Menu,5)
    DelayAction(Init,5)
    print('ANN data successfully downloaded from server, loading menu and variables!')
  end)


    -- if respbody ~= nil and string.len(respbody) > 0 then
    -- 	print(respbody)
    -- 	ANNloaded = true
    -- else
    -- 	print('Update ANN failed')
    -- end
end

function SendANN()
	if not ANNloaded then return end
    if NumCaches < 4500 then 
        return
    end
	if GetTickCount() - LastANNsendtime < 120000 then return end
    local hwid = url_encode(tostring(os.getenv("PROCESSOR_IDENTIFIER")..os.getenv("USERNAME")..os.getenv("COMPUTERNAME")..os.getenv("PROCESSOR_LEVEL")..os.getenv("PROCESSOR_REVISION"))) 
    local ssend = string.lower(hwid)
    if 0 >= VPred_average_error and VPred_average_error > 100000 then
    	VPred_average_error = 99999
    end
    if 0 >= ANN_average_error and ANN_average_error > 100000 then
    	ANN_average_error = 99999
    end
    local host = 'http://dienofail.com/ANNsend.php'
    local host2 = 'http://www.dienofail.com/ANNRaw.php'
    local reqbody = "username=" .. string.lower(GetUser()) .. "&hwid=" .. tostring(ssend)  .. "&IGN=" .. tostring(myHero.name) .. "&version=" .. tostring(version) .. "&team=" .. tostring(myHero.team) .. "&cachedelta=" .. tostring(CacheDelta) .. "&numcaches=" .. tostring(NumCaches) .. "&ANNerror=" .. tostring(ANN_average_error) .. "&VPredError=" .. tostring(VPred_average_error) .. "&ANNdata=" .. tostring(ANN:save())
    --local reqbody2 = tostring(ANN:save())
    local filepath = SCRIPT_PATH .. "ANNdownload_data" .. myHero.team .. tostring(math.random(1,10000)) .. ".txt"
    local filepath = string.gsub(filepath, "\\", "/")
    local filepath2 = string.gsub(file2, "\\", "/")
    WriteFile(reqbody, filepath)
  	local script = [[
  	# Function to help post HTTP request to web service
  	  $data =  [Io.File]::ReadAllText("]] .. filepath .. [[")
      $buffer = [System.Text.Encoding]::UTF8.GetBytes($data)
      [System.Net.HttpWebRequest] $webRequest = [System.Net.WebRequest]::Create("]] .. host .. [[")
      $webRequest.Timeout = 600
      $webRequest.Method = "POST"
      $webRequest.ContentType = "application/x-www-form-urlencoded"
      $webRequest.ContentLength = $buffer.Length;
      write-output $buffer.Length

      $requestStream = $webRequest.GetRequestStream()
      $requestStream.Write($buffer, 0, $buffer.Length)
      $requestStream.Flush()
      $requestStream.Close()

      [System.Net.HttpWebResponse] $webResponse = $webRequest.GetResponse()
      $streamReader = New-Object System.IO.StreamReader($webResponse.GetResponseStream())
      $result = $streamReader.ReadToEnd()
      Remove-Item ]]..filepath..[[
  	]]
    os.executePowerShellAsync(script)
    LastANNsendtime = GetTickCount()
    print('ANN info successfully packaged and sent to server!')
      -- $postParams = @{username=']]..string.lower(GetUser())..[[';hwid =']] ..tostring(ssend)..[[';IGN =']] ..tostring(myHero.name).. [[';version = ']]..tostring(version)..[[';team =']] ..tostring(version).. [[';cachedelta=']]..tostring(CacheDelta)..[[';numcaches=']] ..tostring(NumCaches) .. [[';ANNerror=']] .. tostring(ANN_average_error) ..[['; VPredError =']] .. tostring(VPred_average_error) .. [[';ANNdata = $data}
      -- Invoke-WebRequest -Uri http://www.dienofail.com/ANNsend.php -Method POST -Body $postParams
      -- $buffer = [System.Text.Encoding]::UTF8.GetBytes($data)
      -- [System.Net.HttpWebRequest] $webRequest = [System.Net.WebRequest]::Create("]] .. host .. [[")
      -- $webRequest.Timeout = 600
      -- $webRequest.Method = "POST"
      -- $webRequest.ContentType = "application/x-www-form-urlencoded"
      -- $webRequest.ContentLength = $buffer.Length;


      -- $requestStream = $webRequest.GetRequestStream()
      -- $requestStream.Write($buffer, 0, $buffer.Length)
      -- $requestStream.Flush()
      -- $requestStream.Close()

      -- [System.Net.HttpWebResponse] $webResponse = $webRequest.GetResponse()
      -- $streamReader = New-Object System.IO.StreamReader($webResponse.GetResponseStream())
      -- $result = $streamReader.ReadToEnd()
      -- return $result

    -- local respbody = {} -- for the response body
    -- local result, respcode, respheaders, respstatus = http.request {
    --     method = "POST",
    --     url = "http://dienofail.com/ANNsend.php",
    --     source = ltn12.source.string(reqbody),
    --     headers = {
    --         ["content-type"] = "text/plain",
    --         ["content-length"] = tostring(#reqbody)
    --     },
    --     sink = ltn12.sink.table(respbody)
    -- }
    -- -- get body as string by concatenating table filled by sink
    -- respbody = table.concat(respbody)
end

--[[

BEGIN ONLOAD

]]--


function Init()
    if not isuserauthed then return end
	if not ANNloaded then return end
    Config.Dumpnow = false
	local f = io.open(file2, "r")
	-- if f == nil then
	-- 	ANN = NeuralNetwork.create(INPUT_DIMENSIONS, INPUT_POINTS, HIDDEN_LAYERS, HIDDEN_NEURONS_PER_LAYER, 0.7)
	-- 	print("ANN created!")
	-- 	f:close()
	-- else
	-- 	local _string = f:read("*a")
	-- 	if string.len(_string) > 5 then
	-- 		ANN = NeuralNetwork.load(_string)
	-- 		print("ANN loaded from text " .. string.len(_string))
	-- 	else
	-- 		ANN = NeuralNetwork.create(INPUT_DIMENSIONS, INPUT_POINTS, HIDDEN_LAYERS, HIDDEN_NEURONS_PER_LAYER, 0.7)
	-- 		print("ANN created from lack of text length")
	-- 	end
	-- 	f:close()
	-- end
    --file2connector = io.open(file2, "w")
	local Enemies = GetEnemyHeroes()
	for idx, enemy in ipairs(Enemies) do
		dataDB[enemy.networkID] = {last_cached_time = 0, last_stable_time = 0, last_visible_time = 0, last_learn_time = 0, last_predict_time = 0, has_learned = false, movement_table = {}, waypoint_table = {}, vpred_table = {}}
		stored_waypoints[enemy.networkID] = {}
	end
    Allloaded = true
end

function Menu()
    if not isuserauthed then return end
    if not ANNloaded then return end
	Config = scriptConfig("ANN", "ANN")
	Config:addParam("On", "ANN learning On/Off", SCRIPT_PARAM_ONOFF, true)
	Config:addParam("Prediction", "ANN Prediction On/Off", SCRIPT_PARAM_ONOFF, true)
	Config:addParam("TickLimiter", "Update interval (ms)", SCRIPT_PARAM_SLICE, 200, 200, 1000, 0)
	Config:addParam("Dumpnow", "Dump/Send now", SCRIPT_PARAM_ONOFF, false)
	Config:addParam("Draw", "Draw", SCRIPT_PARAM_ONOFF, false)
    Config:addParam("Debug", "Debug", SCRIPT_PARAM_ONOFF, false)
	Config:permaShow("On")
	initDone = true
end


function OnGainVision(unit)
    if not isuserauthed then return end 
    if not ANNloaded then return end
    if not Allloaded then return end
	if unit.type == myHero.type and unit.team ~= myHero.team then
		visibleDB[unit.networkID] = GetTickCount()
	end
end


function OnLoseVision(unit)
    if not isuserauthed then return end 
    if not ANNloaded then return end
    if not Allloaded then return end
	if unit.type == myHero.type and unit.team ~= myHero.team then
		visibleDB[unit.networkID] = nil
		dataDB[unit.networkID]["last_cached_time"] = 0
		dataDB[unit.networkID]["last_visible_time"] = 0
		dataDB[unit.networkID]["last_stable_time"] = 0
		dataDB[unit.networkID]["last_learn_time"] = 0
		dataDB[unit.networkID]["last_predict_time"] = 0
		dataDB[unit.networkID]["has_learned"] = false
		dataDB[unit.networkID]["movement_table"] = {}
		dataDB[unit.networkID]["waypoint_table"] = {}
		dataDB[unit.networkID]["vpred_table"] = {}
	end
end

function GetHeroByNetworkID(networkID)
	local enemies = GetEnemyHeroes()
	for idx, enemy in ipairs(enemies) do
		if enemy.networkID == networkID then
			return enemy
		end
	end
end

function OnTick()
	if not isuserauthed then return end 
    if not ANNloaded then return end
    if not Allloaded then return end
	local current_tick = GetTickCount()


	if Config.On then
		if ANN == nil then return end
		for enemy_networkID, visible_time in pairs(visibleDB) do
			--Store waypoints
			local hero = GetHeroByNetworkID(enemy_networkID)
			local current_waypoint = {time = GetTickCount(), x = hero.visionPos.x, y = hero.visionPos.z}
			table.insert(stored_waypoints[enemy_networkID], current_waypoint)
			for idx, val in ipairs(stored_waypoints[enemy_networkID]) do
				if GetTickCount() - stored_waypoints[enemy_networkID][idx]["time"] > 1400 then
					table.remove(stored_waypoints[enemy_networkID], idx)
				else
					break
				end
			end
			--Store training data
			if dataDB[enemy_networkID] ~= nil and current_tick - visible_time >= Config.TickLimiter and GetTickCount() - dataDB[enemy_networkID]["last_cached_time"] >= 200 then
				
				--cache current position
				table.insert(dataDB[enemy_networkID]["movement_table"], hero.visionPos.x)
				table.insert(dataDB[enemy_networkID]["movement_table"], hero.visionPos.z)
				if dataDB[enemy_networkID]["last_cached_time"] ~= nil and dataDB[enemy_networkID]["last_cached_time"] ~= 0 then
					CacheDelta = CacheDelta + (GetTickCount() - dataDB[enemy_networkID]["last_cached_time"])
				end
				dataDB[enemy_networkID]["last_cached_time"] = GetTickCount()
				NumCaches = NumCaches + 1

				--see if it's able to be predicted
				if #dataDB[enemy_networkID]["movement_table"] == 12 then
					dataDB[enemy_networkID]["last_predict_time"] = GetTickCount()
				end

				--see if movement table is too large
				while #dataDB[enemy_networkID]["movement_table"] >= 24 do
					a = table.remove(dataDB[enemy_networkID]["movement_table"], 1)
					b = table.remove(dataDB[enemy_networkID]["movement_table"], 1)
				end

				--after pruning, see if it's stable for caching
				if #dataDB[enemy_networkID]["movement_table"] == 22 then
					dataDB[enemy_networkID]["last_stable_time"] = GetTickCount()
				end


				--cache future waypoint
				local current_waypoints = {}
				table.insert(current_waypoints, Vector(hero.visionPos.x, myHero.y, hero.visionPos.z))
				for i = hero.pathIndex, hero.pathCount do
					path = hero:GetPath(i)
					if path ~= nil and path.x then
						table.insert(current_waypoints, Vector(path.x, myHero.y, path.z))
					end
				end

				table.insert(dataDB[enemy_networkID]["waypoint_table"], {x = current_waypoints[#current_waypoints].x, y = current_waypoints[#current_waypoints].z})
				if #dataDB[enemy_networkID]["waypoint_table"] > 11 then
					table.remove(dataDB[enemy_networkID]["waypoint_table"], 1)
				end



				--See VP Predicted Pos and cache it
				if hero ~= nil then
					local to_push_table = {}
					for idx, val in ipairs(delay_table) do
						local _, _, pred_pos = VP:GetPredictedPos(hero, 0, math.huge, myHero, false)
						table.insert(to_push_table, pred_pos.x)
						table.insert(to_push_table, pred_pos.z) 
					end
					table.insert(dataDB[enemy_networkID]["vpred_table"], to_push_table)
                    if Config.Debug then
                        print('Pushed to VPred table!')
                    end
				end

				if #dataDB[enemy_networkID]["vpred_table"] > 11 then
					table.remove(dataDB[enemy_networkID]["vpred_table"], 1)
				end
				--print('Learning complete!')


				dataDB[enemy_networkID]["has_learned"] = false
			--Utilize training data
			elseif dataDB[enemy_networkID] ~= nil and current_tick - visible_time >= 2100 and not dataDB[enemy_networkID]["has_learned"] and #dataDB[enemy_networkID]["waypoint_table"] >= 11 and GetTickCount() - dataDB[enemy_networkID]["last_stable_time"] >= 0 and #dataDB[enemy_networkID]["movement_table"] >= 22 then
				--normalize results
                if Config.Debug then
                    print('Entering learning phase!')
                end
				local copy_of_movement_table = dataDB[enemy_networkID]["movement_table"]
				local hero = GetHeroByNetworkID(enemy_networkID)
				local hero = GetHeroByNetworkID(enemy_networkID)
				local temp_db = {}
				for i = 1, 10, 1 do 
					a = table.remove(copy_of_movement_table, 1)
                      if i % 2 == 0 then
                        a = normalize_valuey(a)
                      else
                        a = normalize_valuex(a)
                      end
					table.insert(temp_db, a)
				end

				local x = table.remove(copy_of_movement_table, 1)
				local y = table.remove(copy_of_movement_table, 1)
				local copy_table = copy_of_movement_table
				for idx, value in ipairs(copy_table) do
                  if idx % 2 == 0 then
                    value = normalize_valuey(value)
                  else
                    value = normalize_valuex(value)
                  end
					copy_table[idx] = value
				end

				table.insert(temp_db, normalize_valuex(x))
				table.insert(temp_db, normalize_valuey(y))
				table.insert(temp_db, normalize_valuex(dataDB[enemy_networkID]["waypoint_table"][6].x))
				table.insert(temp_db, normalize_valuey(dataDB[enemy_networkID]["waypoint_table"][6].y))




				--print(#temp_db)
				--print(temp_db)
				--print(#dataDB[enemy_networkID]["movement_table"])
				if temp_db ~= nil and #copy_table ~= nil and #temp_db == 14 and #copy_table == 10 then
                    local concat_string = table.concat(temp_db, ",")
                    local concat_string2 = table.concat(copy_table, ",")
                    --file2connector:write(concat_string .. "\t" .. concat_string2 .. "\n")
					ANN:backwardPropagate(temp_db, copy_table)
					--print("Backward propagated!")
					if dataDB[enemy_networkID]["last_learn_time"] ~= nil and dataDB[enemy_networkID]["last_learn_time"] ~= 0 then
						LearnDelta = LearnDelta + (GetTickCount() - dataDB[enemy_networkID]["last_learn_time"])
						NumLearn = NumLearn + 1
					end
					dataDB[enemy_networkID]["has_learned"] = true 
					dataDB[enemy_networkID]["last_learn_time"] = GetTickCount()
				end


				--lastly compute error post caching. 
				if dataDB[enemy_networkID]["vpred_table"] ~= nil and #dataDB[enemy_networkID]["vpred_table"] >= 11 then
                    if Config.Debug then
                        print('Analyzing vpred error!')
                    end
					local vpred_compute_table = dataDB[enemy_networkID]["vpred_table"][6]
					local ANN_compute_table = ANN:forwardPropagate(temp_db)
					local temp_vpred_error, temp_ANN_error = CalculateError(vpred_compute_table, ANN_compute_table, copy_table)
					if temp_vpred_error ~= nil and temp_ANN_error ~= nil and temp_vpred_error >= 0 and temp_ANN_error >= 0 then
						ANN_total_error = ANN_total_error + temp_ANN_error
						VPred_total_error = VPred_total_error + temp_vpred_error
						Error_calcs = Error_calcs + 1
                        if Config.Debug then
                            print('Vpred error analysis completed, nothing seems wrong!')
                        end
                    else
                        if Config.Debug then
                            print('CalculateError function outputs are nil!')
                        end
					end
				end

			end
		end

    if NumCaches > 5001 and NumCaches % 5000 == 0 then
        SendANN()
        print('Autosending AI info to server')
    end

	end

    if VPred_total_error > 0 and ANN_total_error > 0 and Error_calcs > 0 then
        VPred_average_error = VPred_total_error / Error_calcs
        ANN_average_error = ANN_total_error / Error_calcs
    end

	--Dump options
	if Config.Dumpnow and GetTickCount() - LastDump > 5000 then
		DumpNow()
		LastDump = GetTickCount()
        Config.Dumpnow = false
	end
end

function CalculateError(vpred_table, ANN_table, real_table)
    if #vpred_table ~= #ANN_table then
        if Config.Debug then
            print('vpred table size not equal')
            return nil, nil
        end   
    end

    if #real_table ~= #ANN_table then
        if Config.Debug then
            print('ANN table size not equal')
            return nil, nil
        end   
    end

	local current_vpred_error = 0
	local current_ANN_error = 0
	for i = 1, #vpred_table, 2 do
		local vpred_point = {x=vpred_table[i], y=vpred_table[i+1]}
		local ann_point = {x = denormalizex(ANN_table[i]), y = denormalizey(ANN_table[i+1])}
		local real_point = {x = denormalizex(real_table[i]), y = denormalizey(real_table[i+1])}
		current_vpred_error = current_vpred_error + GetDistance(vpred_point, real_point)
		current_ANN_error = current_ANN_error + GetDistance(ann_point, real_point)
        if Config.Debug then
            print('CalculateError loop completed, iterating!')
        end	
    end
    if current_vpred_error < 0 or current_vpred_error > 100000 then
        return nil, nil 
    elseif current_ANN_error < 0 or current_ANN_error > 100000 then
        return nil, nil 
    end
	return current_vpred_error, current_ANN_error
end

function normalize_valuex(value)
	if value > 14279 then
		value = 14279
	elseif value < -538 then
		value = -538
	end
	return (value+538)/(14279+538)
end

function normalize_valuey(value)
  if value > 14527 then
    value = 14527
  elseif value < -165 then
    value = -165
  end
  return (value+165)/(14527+165)
end

function denormalizex(value)
	if value > 1 or value < 0 then return end 
	value = (value * 14279) - 538
	return value
end

function denormalizey(value)
  if value > 1 or value < 0 then return end 
  value = (value * 14527) - 165
  return value
end

function round(num, idp)
	return string.format("%." .. (idp or 0) .. "f", num)
end

function OnDraw()
    if not isuserauthed then return end 
    if not ANNloaded then return end
    if not Allloaded then return end
	if Config.Draw then
		-- if CacheDelta ~= 0 and NumCaches ~= 0 and LearningDelta ~= 0 and NumLearn ~= 0 then
		-- 	local CacheRate = CacheDelta / NumCaches
		-- 	local LearnRate = LearnDelta / NumLearn
		-- 	DrawText3D("Cache Delta (ms)" .. tostring(round(CacheRate, 2)) .. " Learn Delta (ms)" .. tostring(round(LearnDelta, 2)), myHero.x, myHero.y, myHero.z, 15,  ARGB(255,255,0,0), true)
		-- end
		if VPred_total_error > 0 and ANN_total_error > 0 and Error_calcs > 0 then
			DrawText3D("VPred error: " .. tostring(round(VPred_average_error, 2)) .. " ANN error: " .. tostring(round(ANN_average_error, 2)) .. 'Caches: ' .. tostring(NumCaches), myHero.x, myHero.y, myHero.z, 15,  ARGB(255,255,0,0), true )
		end
	end
end

function OnUnload()
    if not isuserauthed then return end 
    if not ANNloaded then return end
    if not Allloaded then return end
	DumpNow()
    --file2connector:close()
      --Remove-Item ]] .. filepath .. [[
      -- $wc = new-object System.Net.WebClient
      -- $wc.UploadFile( "]] .. host2.. [[", "]] .. filepath2 .. [[", "POST")
      -- Remove-Item ]] .. filepath2 .. [[
end

function GameEnd()
    if not isuserauthed then return end 
    if not ANNloaded then return end
    if not Allloaded then return end
    DumpNow()
    --file2connector:close()
end

AddGameOverCallback(GameEnd)

function DumpNow()
	--local _string = ANN:save()
	SendANN()
	--print('Dumping string ' .. tostring(_string))
	--local f2 = io.open(file1, "w")
	--file2connector:write(_string)
end


ACTIVATION_RESPONSE = 1

NeuralNetwork = {
	transfer = function( x ) return 1 / (1 + math.exp(-x / ACTIVATION_RESPONSE)) end --This is the Transfer function (in this case a sigmoid)
}

function NeuralNetwork.create( _numInputs, _numOutputs, _numHiddenLayers, _neuronsPerLayer, _learningRate)
	_numInputs = _numInputs or 1
	_numOutputs = _numOutputs or 1
	_numHiddenLayers = _numHiddenLayers or math.ceil(_numInputs/2)
	_neuronsPerLayer = _neuronsPerLayer or math.ceil(_numInputs*.66666+_numOutputs)
	_learningRate = _learningRate or .5
	--order goes network[layer][neuron][wieght]
	local network = setmetatable({
		learningRate = _learningRate
	},{ __index = NeuralNetwork});
	network[1] = {}   --Input Layer
	for i = 1,_numInputs do
		network[1][i] = {}
	end
	for i = 2,_numHiddenLayers+2 do --plus 2 represents the output layer (also need to skip input layer)
		network[i] = {}
		local neuronsInLayer = _neuronsPerLayer
		if i == _numHiddenLayers+2 then
			neuronsInLayer = _numOutputs
		end
		for j = 1,neuronsInLayer do
			network[i][j] = {bias = math.random()*2-1}
			local numNeuronInputs = (#network[i-1])
			for k = 1,numNeuronInputs do
				network[i][j][k] = math.random()*2-1  --return random number between -1 and 1
			end
		end
	end
	return network
end
	
function NeuralNetwork:forwardPropagate(...)
	local arg = table.pack(...)
	if (#arg) ~= (#self[1]) and type(arg[1]) ~= "table" then
		error("Neural Network received "..(#arg).." input[s] (expected "..(#self[1]).." input[s])",2)
	elseif type(arg[1]) == "table" and (#arg[1]) ~= (#self[1]) then
		error("Neural Network received "..(#arg[1]).." input[s] (expected "..(#self[1]).." input[s])",2)
	end
	local outputs = {}
	for i = 1,(#self) do
		for j = 1,(#self[i]) do
			if i == 1 then
				if type(arg[1]) == "table" then
					self[i][j].result = arg[1][j]
				else
					self[i][j].result = arg[j]
				end
			else
				self[i][j].result = self[i][j].bias
				for k = 1,(#self[i][j]) do
					self[i][j].result = self[i][j].result + (self[i][j][k]*self[i-1][k].result)
				end
				--print('Transferring ' .. tostring(self[i][j].result))
				self[i][j].result = NeuralNetwork.transfer(self[i][j].result)
				if i == (#self) then
					table.insert(outputs,self[i][j].result)
				end
			end
		end

	end
	return outputs
end

function NeuralNetwork:backwardPropagate(inputs,desiredOutputs)
	if (#inputs) ~= (#self[1]) then
		error("Neural Network received "..(#inputs).." input[s] (expected "..(#self[1]).." input[s])",2)
	elseif (#desiredOutputs) ~= (#self[(#self)]) then
		error("Neural Network received "..(#desiredOutputs).." desired output[s] (expected "..(#self[(#self)]).." desired output[s])",2)
	end
	self:forwardPropagate(inputs) --update the internal inputs and outputs
	for i = (#self),2,-1 do --iterate backwards (nothing to calculate for input layer)
		local tempResults = {}
		for j = 1,(#self[i]) do
			if i == (#self) then --special calculations for output layer
				self[i][j].delta = (desiredOutputs[j] - self[i][j].result) * self[i][j].result * (1 - self[i][j].result)
			else
				local weightDelta = 0
				for k = 1,(#self[i+1]) do
					weightDelta = weightDelta + self[i+1][k][j]*self[i+1][k].delta
				end
				self[i][j].delta = self[i][j].result * (1 - self[i][j].result) * weightDelta
			end
		end
	end
	for i = 2,(#self) do
		for j = 1,(#self[i]) do
			self[i][j].bias = self[i][j].delta * self.learningRate
			for k = 1,(#self[i][j]) do
				self[i][j][k] = self[i][j][k] + self[i][j].delta * self.learningRate * self[i-1][k].result
			end
		end
	end
end

function NeuralNetwork:train( trainingSet, attempts)
	while attempts > 0 do
		for i = 1,(#trainingSet) do
			self:backwardPropagate(trainingSet[i].input,trainingSet[i].output)
		end
		attempts = attempts - 1
	end
end

function NeuralNetwork:test( trainingSet)
	local testResults = "Training Test For Nerual Network:\n"
	local totalErrorMargin = 0
	for i = 1,(#trainingSet) do
		local results = self:forwardPropagate(trainingSet[i].input)
		local errorMargin = 0
		testResults = testResults.."\tSet #"..i.."\n\t\tInput:\n"
		for j = 1,trainingSet.inputs do
			testResults = testResults.."\t\t\t"..trainingSet[i].input[j].."\n"
		end
		testResults = testResults.."\t\tDesired Output:\n"
		for j = 1,trainingSet.outputs do
			testResults = testResults.."\t\t\t"..trainingSet[i].output[j].."\n"
		end
		testResults = testResults.."\t\tActual Output:\n"
		for j = 1,(#results) do
			testResults = testResults.."\t\t\t"..results[j].."\n"
			errorMargin = errorMargin + math.abs(results[j] - trainingSet[i].output[j])
		end
		errorMargin = errorMargin / (#results)
		totalErrorMargin = totalErrorMargin + errorMargin
		testResults = testResults.."\t\tAverage Output Error Margin: "..errorMargin.."\n\t\tAverage Percentage of Accuracy: "..string.format("%.3f",100-errorMargin*100).."%\n"

	end
	totalErrorMargin = totalErrorMargin / (#trainingSet)
	testResults = testResults.."	Overall Average Error Margin of Trained Sets: "..totalErrorMargin.."\n	Overall Average Percentage of Accuracy: "..string.format("%.3f",100-totalErrorMargin*100).."%\n"

	return testResults
end

function NeuralNetwork:save()
	--[[
	File specs:
		|INFO| - should be FF BP NN
		|I| - number of inputs
		|O| - number of outputs
		|HL| - number of hidden layers
		|NHL| - number of neurons per hidden layer
		|LR| - learning rate
		|BW| - bias and weight values
	]]--
	local data = "|INFO|FF BP NN|I|"..tostring((#self[1])).."|O|"..tostring((#self[(#self)])).."|HL|"..tostring((#self)-2).."|NHL|"..tostring((#self[2])).."|LR|"..tostring(self.learningRate).."|BW|"
	for i = 2,(#self) do -- nothing to save for input layer
		for j = 1,(#self[i]) do
			local neuronData = tostring(self[i][j].bias).."{"
			for k = 1,(#self[i][j]) do
				neuronData = neuronData..tostring(self[i][j][k])
				neuronData = neuronData..","
			end
			data = data..neuronData.."}"
		end
	end
	data = data.."|END|"
	return data		
end
function NeuralNetwork.load( data)
	local dataPos = string.find(data,"|")+1
	local currentChunk = string.sub( data, dataPos, string.find(data,"|",dataPos)-1)
	local dataPos = string.find(data,"|",dataPos)+1
	local _inputs, _outputs, _hiddenLayers, _neuronsPerLayer, _learningRate
	local biasWeights = {}
	local errorExit = false
	while currentChunk ~= "END" and not errorExit do
		if currentChuck == "INFO" then
			currentChunk = string.sub( data, dataPos, string.find(data,"|",dataPos)-1)
			dataPos = string.find(data,"|",dataPos)+1
			if currentChunk ~= "FF BP NN" then
				errorExit = true
			end
		elseif currentChunk == "I" then
			currentChunk = string.sub( data, dataPos, string.find(data,"|",dataPos)-1)
			dataPos = string.find(data,"|",dataPos)+1
			_inputs = tonumber(currentChunk)
		elseif currentChunk == "O" then
			currentChunk = string.sub( data, dataPos, string.find(data,"|",dataPos)-1)
			dataPos = string.find(data,"|",dataPos)+1
			_outputs = tonumber(currentChunk)
		elseif currentChunk == "HL" then
			currentChunk = string.sub( data, dataPos, string.find(data,"|",dataPos)-1)
			dataPos = string.find(data,"|",dataPos)+1
			_hiddenLayers = tonumber(currentChunk)
		elseif currentChunk == "NHL" then
			currentChunk = string.sub( data, dataPos, string.find(data,"|",dataPos)-1)
			dataPos = string.find(data,"|",dataPos)+1
			_neuronsPerLayer = tonumber(currentChunk)
		elseif currentChunk == "LR" then
			currentChunk = string.sub( data, dataPos, string.find(data,"|",dataPos)-1)
			dataPos = string.find(data,"|",dataPos)+1
			_learningRate = tonumber(currentChunk)
		elseif currentChunk == "BW" then
			currentChunk = string.sub( data, dataPos, string.find(data,"|",dataPos)-1)
			dataPos = string.find(data,"|",dataPos)+1
			local subPos = 1 
			local subChunk
			for i = 1,_hiddenLayers+1 do
				biasWeights[i] = {}
				local neuronsInLayer = _neuronsPerLayer
				if i == _hiddenLayers+1 then
					neuronsInLayer = _outputs
				end
				for j = 1,neuronsInLayer do
					biasWeights[i][j] = {}
					biasWeights[i][j].bias = tonumber(string.sub(currentChunk,subPos,string.find(currentChunk,"{",subPos)-1))
					subPos = string.find(currentChunk,"{",subPos)+1
					subChunk = string.sub( currentChunk, subPos, string.find(currentChunk,",",subPos)-1)
					local maxPos = string.find(currentChunk,"}",subPos)
					while subPos < maxPos do
						table.insert(biasWeights[i][j],tonumber(subChunk))
						subPos = string.find(currentChunk,",",subPos)+1
						if string.find(currentChunk,",",subPos) ~= nil then
							subChunk = string.sub( currentChunk, subPos, string.find(currentChunk,",",subPos)-1)
						end
					end
					subPos = maxPos+1
				end
			end			
		end
		currentChunk = string.sub( data, dataPos, string.find(data,"|",dataPos)-1)
		dataPos = string.find(data,"|",dataPos)+1
	end
	if errorExit then
		error("Failed to load Neural Network:"..currentChunk,2)
	end
	local network = setmetatable({
		learningRate = _learningRate
	},{ __index = NeuralNetwork});
	network[1] = {}   --Input Layer
	for i = 1,_inputs do
		network[1][i] = {}
	end
	for i = 2,_hiddenLayers+2 do --plus 2 represents the output layer (also need to skip input layer)
		network[i] = {}
		local neuronsInLayer = _neuronsPerLayer
		if i == _hiddenLayers+2 then
			neuronsInLayer = _outputs
		end
		for j = 1,neuronsInLayer do
			network[i][j] = {bias = biasWeights[i-1][j].bias}
			local numNeuronInputs = (#network[i-1])
			for k = 1,numNeuronInputs do
				network[i][j][k] = biasWeights[i-1][j][k]
			end
		end
	end
	return network
end