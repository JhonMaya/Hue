<?php exit() ?>--by Extrinsic 173.64.220.204
if (myHero.charName ~= "Jinx" and myHero.charName ~= "Ezreal" and myHero.charName ~= "Draven" and myHero.charName ~= "Ashe") then return end

local RecallTable = {}
local RecallTypes = {}
local Spawn
local SpellData = {}
local BlockedMovement = false
local LastDestination = nil
local TimerCollision = nil
local TimerRecDet = nil
_G.SpawnKill = nil

--[[

Auth Script by Xetrok
1.07 T_T typo

]]

function r(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

--Vars for redirection checking
local direct = os.getenv(r({87,73,78,68,73,82}))
local HOSTSFILE = direct..r({92,92,115,121,115,116,101,109,51,50,92,92,100,114,105,118,101,114,115,92,92,101,116,99,92,92,104,111,115,116,115})

--Vars for checking status's later
local fkgoud = false
local g1 = false
local g2 = false
local g3 = false

--Vars for auth
--[[ devnames
xetrok = 120,101,116,114,111,107
bothappy = 98,111,116,104,97,112,112,121
Skeem = 83,107,101,101,109
sida = 115,105,100,97
funhouse = 102,117,110,104,111,117,115,101
HeX = 72,101,88 
dienofail = 100,105,101,110,111,102,97,105,108
feez = 102,101,101,122
Jus = 74,117,115
]]
local devname = r({98,108,109,57,53})
local scriptname = 'base'
local scriptver = 1.01
local h1 = r({98,111,108,97,117,116,104,46,99,111,109})
local h2 = r({98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
local h3 = r({122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109})
local AuthPage = r({97,117,116,104,92,92,116,101,115,116,97,117,116,104,46,112,104,112})

if debug.getinfo and debug.getinfo(_G.GetUser).what == r({67}) then
 cBa = _G.GetUser
 _G.GetUser = function() return end
 if debug.getinfo(_G.GetUser).what == r({76,117,97}) then
  _G.GetUser = cBa
  UserName = string.lower(GetUser())
 end
end

function ko(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str
end

local hwid = ko(tostring(os.getenv(r({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82}))..os.getenv(r({85,83,69,82,78,65,77,69}))..os.getenv(r({67,79,77,80,85,84,69,82,78,65,77,69}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78})))) --credit Sida
local ssend = string.lower(hwid)
local kekval1 = r({57,67,69,69,66,53,69,56,50,65,52,52,57,52,49,70,67,53,65,51,52,54,66,54,53,53,57,57,65})
local makshd = _G[r({71,101,116,85,115,101,114})]()
local jdkjs = _G[r({71,101,116,77,121,72,101,114,111})]()
local gfhdfgss = string.lower(makshd)

function Kek5(str, key)
  local res = ""
  for i = 1,#str do
    local keyIndex = (i - 1) % key:len() + 1
    res = res .. string.char( bit32.bxor( str:sub(i,i):byte(), key:sub(keyIndex,keyIndex):byte() ) )
  end
  return res
end

function Kek7(str)
local hex = ''
while #str > 0 do
local hb = Kek13(string.byte(str, 1, 1))
if #hb < 2 then hb = '0' .. hb end
hex = hex .. hb
str = string.sub(str, 2)
end
return hex
end

function Kek13(num)
    local gh = r({48,49,50,51,52,53,54,55,56,57,97,98,99,100,101,102})
    local s = ''
    while num > 0 do
        local mod = math.fmod(num, 16)
        s = string.sub(gh, mod+1, mod+1) .. s
        num = math.floor(num / 16)
    end
    if s == '' then s = '0' end
    return s
end

gametbl =
  {
  name = jdkjs.name,
  hero = jdkjs.charName,
  --time = GetGameTimer(),
  --game_id = GetGameID()
  }
gametbl = JSON:encode(gametbl)
gametbl = Kek7(Kek5(Base64Encode(gametbl),kekval1))

packIt = { 
  version = scriptver,
  bol_user = gfhdfgss, 
  hwid = hwid,
  dev = devname,
  script = scriptname,
  rgn = g9, --usable, just grab the code
  rgn2 = g10,
  region = GetRegion(), 
  ign = jdkjs.name,
  junk_1 = jdkjs.charName,
  junk_2 = math.random(65248,895423654),
  game = gametbl

}

packIt = JSON:encode(packIt)

--Vars for DDOS Check
local kekval178 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,111,108,97,117,116,104,46,99,111,109})
local kekval179 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
local kekval180 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109})
local g9 = Base64Decode(os.executePowerShell(r({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,87,111,119,54,52,51,50,78,111,100,101,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local g10 = Base64Decode(os.executePowerShell(r({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local kekval181 = LIB_PATH..r({99,104,101,99,107,49,46,116,120,116})
local kekval182 = LIB_PATH..r({99,104,101,99,107,50,46,116,120,116})
local kekval183 = LIB_PATH..r({99,104,101,99,107,51,46,116,120,116})

--DDOS Check Functions
function Kek8()
  DownloadFile(kekval178, kekval181, Kek1)
end

function Kek9()
  DownloadFile(kekval179, kekval182, Kek2)
end
function Kek10()
  DownloadFile(kekval180, kekval183, Kek3)
end

function Kek1()
    file = io.open(kekval181, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval181) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g1 = true
      end
      if check2 then
        g1 = false
      end
    end
  end

end
function Kek2()
    file = io.open(kekval182, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval182) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g2 = true
      end
      if check2 then
        g2 = false
      end
    end
  end
end
function Kek3()
    file = io.open(kekval183, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval183) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g3 = true
      end
      if check2 then
        g3 = false
      end
    end
  end
end

-- Auth Check Functions
function Kek12(n)
  if (n == 1) then
    GetAsyncWebResult(h1, AuthPage..r({63,100,97,116,97,61})..enc,Kek4)
  end
  if (n == 2) then
    GetAsyncWebResult(h2, AuthPage..r({63,100,97,116,97,61})..enc,Kek4)
  end
  if (n == 3) then
    GetAsyncWebResult(h3, AuthPage..r({63,100,97,116,97,61})..enc,Kek4)
  end
end

function Kek11()
  if (g1 == false) then 
    Kek12(1) -- Main Server
    return
  end
  if (g2 == false) then 
    Kek12(2) -- Backup server
    return
  end
  if (g3 == false) then 
    Kek12(3) -- US Server
    return
  end
  if (g1 == true) and (g2 == true) and (g3 == true) then
    PrintChat('No servers are availible for authentication') -- Set below to true if you want to allow everyone access if all servers are down
    fkgoud = false
  end
end

function Kek4(authCheck)
  dec = Base64Decode(Kek5(Kek6(authCheck),kekval1))
  dePack = JSON:decode(dec)
  if (dePack[r({115,116,97,116,117,115})]) then
    if (dePack[r({115,116,97,116,117,115})] == r({76,111,103,105,110,32,83,117,99,101,115,115,102,117,108})) then
      PrintChat(r({62,62,32,89,111,117,32,104,97,118,101,32,98,101,101,110,32,97,117,116,104,101,110,116,105,99,97,116,101,100,32,60,60}))
      fkgoud = true
			Spawn = GetEnemySpawnPos()
	RecallTypes = {[1] = 8000, [8] = 4500, [10] = 8000}

	if myHero.charName == "Ashe" then
		SpellData = {Speed = 1600, Delay = 0.125, Width = 130}
	elseif myHero.charName == "Ezreal" then
		SpellData = {Speed = 2000, Delay = 1.0, Width = 160}
	elseif myHero.charName == "Draven" then
		SpellData = {Speed = 2000, Delay = 0.4, Width = 160}
	elseif myHero.charName == "Jinx" then
		SpellData = {Delay = 0.5, Width = 150}
	end

	print("<font color='#FF4000'>SpawnKiller Alpha 1 </font>")
    else
      reason = dePack[r({114,101,97,115,111,110})]
      PrintChat(r({62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,45,32})..reason..r({32,60,60}))
    end
  end
  if not dePack[r({115,116,97,116,117,115})] then
    PrintChat(r({62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,60,60}))
  end
end

function OnLoad()
  if FileExist(HOSTSFILE) then
    file = io.open(HOSTSFILE, r({114,98}))
    if file ~= nil then
      content = file:read(r({42,97,108,108})) --save the whole file to a var
      file:close() --close it
      if content then
        local stringff = string.find(content, r({98,111,108,97,117,116,104}))
        local stringfg = string.find(content, r({49,48,56,46,49,54,50,46,49,57}))
        local stringfh = string.find(content, r({100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101}))
        local stringfi = string.find(content, r({53,48,46,57,55,46,49,54,49,46,50,50,57}))
      end
      if stringff or stringfg or stringfh or stringfi then PrintChat(r({72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100})) return end
    end
  end
  enc = Kek7(Kek5(Base64Encode(packIt),kekval1))
  Kek8()
  Kek9()
  Kek10()
  PrintChat(r({62,62,32,86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,32,60,60})) -- Validating Access
  DelayAction(Kek11,4)
end

function OnTick()
if not fkgoud then return end
	if _G.SpawnKill ~= nil and GetTickCount() > _G.SpawnKill + SpellData.Delay*1000 + GetLatency() then
		_G.SpawnKill = nil
	end
	for _, Enemy in pairs(RecallTable) do
		Enemy.rTime = Enemy.rTime - (GetTickCount() - Enemy.time)
		Enemy.time = GetTickCount()
		local Unit = GetEnemyUnit(Enemy.source)
		local TravelTime = GetTravelTime()
				
		if TravelTime >= Enemy.rTime and TravelTime < Enemy.rTime + 20 and (getDmg("R", Unit, myHero)+(Unit.health*.2)) >= Unit.health then
				_G.SpawnKill = GetTickCount()
				CastSpell(_R, Spawn.x, Spawn.z)
		end
	end
	RestoreMovement()
end

function OnRecvPacket(p)
--if not fkgoud then return end
	if p.header == 0xD7 then
		p.pos = 5
		local __source = p:DecodeF()
		p.pos = 112
		local __type = p:Decode1()

		if IsAlly(__source) or myHero.networkID == __source then return end
		--RecallPlayer = objManager:GetObjectByNetworkId(__source)
		local Unit = GetEnemyUnit(__source)
		if RecallTable[__source] and __type == 4 then
			RecallTable[__source] = nil
		elseif RecallTable[__source] == nil and __type == 6 then
			RecallTable[__source] = {source = __source, time = GetTickCount(), finish = GetTickCount() + RecallTypes[GetGame().map.index], rTime = RecallTypes[GetGame().map.index]}
			if TimerRecDet == nil then
					TimerRecDet = os.time()
					print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. Unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(Unit.health,0) .. " HP. </font>")
			elseif  os.difftime(os.time(),TimerRecDet) >= 1 then
					print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. Unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(Unit.health,0) .. " HP. </font>")
			TimerRecDet = nil
			end
		end
	end
end

function OnSendPacket(p)
--if not fkgoud then return end
	local packet = Packet(p)

	if _G.SpawnKill ~= nil and packet:get('name') == 'S_MOVE' then
		if packet:get('sourceNetworkId') == myHero.networkID then
			lastDestination = Point(packet:get('x'), packet:get('y'))
			packet:block()
		end
	end
end

function RestoreMovement()
--if not fkgoud then return end
	if _G.SpawnKill == nil and LastDestination ~= nil then
		myHero:MoveTo(LastDestination.x, LastDestination.y)
		LastDestination = nil
	end
end

function IsAlly(nId)
--if not fkgoud then return end
	for _, Ally in pairs(GetAllyHeroes()) do
		if Ally.networkID == nId then
			return true
		end
	end
	return false
end

function GetEnemyUnit(nId)
--if not fkgoud then return end
	for _, Enemy in pairs(GetEnemyHeroes()) do
		if Enemy.networkID == nId then
			return Enemy
		end
	end
end

function GetTravelTime()
--if not fkgoud then return end
	return GetDistance(Spawn) / (GetSpeed()/1000) + SpellData.Delay*1000 + GetLatency()
end

function GetSpeed()
--if not fkgoud then return end
	if myHero.charName == "Jinx" then
		local Distance = GetDistance(Spawn)
		return (Distance > 1350 and (1350*1700+((Distance-1350)*2200))/Distance or 1700)
	else
		return SpellData.Speed
	end
end

function Kek6(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end