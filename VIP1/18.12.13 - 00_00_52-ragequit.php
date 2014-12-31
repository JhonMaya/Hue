<?php exit() ?>--by ragequit 71.63.104.40
if myHero.charName ~= "Zed" then return end

class 'Plugin'

SACstate = 3
local DoneInit = false
local Target
local SkillQ, SkillW
local NextClone = 0
local CLONE_W, CLONE_R = 1, 2
local CloneW, CloneR = nil, nil
local WBuff, RBuff = nil, nil


function Plugin:__init()
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 900, "Razor Shuriken", AutoCarry.SPELL_LINEAR, 0, false, false, 1.742, 235, 100, false)
	SkillW = AutoCarry.Skills:NewSkill(false, _W, 550, "Living Shadow", AutoCarry.SPELL_LINEAR, 0, false, false, 1.5, 200, 100, false)
	SkillWHarass = AutoCarry.Skills:NewSkill(false, _W, 900, "Living Shadow", AutoCarry.SPELL_LINEAR, 0, false, false, 1.5, 200, 100, false)
	AutoCarry.Crosshair:SetSkillCrosshairRange(900)

	AdvancedCallback:bind("OnGainBuff", OnGainBuff)
	AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)

	for _, Item in pairs(AutoCarry.Items.ItemList) do
		if Item.ID == 3153 then
			Item.Enabled = false
		end
	end
end

function Plugin:OnTick()
	Target = AutoCarry.Crosshair:GetTarget()
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	WREADY = (myHero:CanUseSpell(_W) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)

	if Target then
		if AutoCarry.Keys.AutoCarry and Menu.Combo then
			Combo(Target)
		elseif (AutoCarry.Keys.MixedMode and Menu.HarassMM) or (AutoCarry.Keys.LastHit and Menu.HarassLH) or (AutoCarry.Keys.LaneClear and Menu.HarassLC) then
			Harass(Target)
		end
	if Menu.autoE then CheckE(Target) end
	end
end

function Combo(Target)
	if not CloneR and not RBuff and myHero:GetSpellData(_R).name ~= "ZedR2" and myHero:CanUseSpell(_R) == READY then
		CastSpell(_R, Target)
		return
	end

	if CloneR or myHero:CanUseSpell(_R) ~= READY then
		local Botrk = GetInventorySlotItem(3153)
		if Botrk then
			CastSpell(Botrk, Target)
		end
	end

	DoW(Target)

	if CloneW or myHero:CanUseSpell(_W) ~= READY then
		SkillQ:Cast(Target)
	end

	CheckE(Target)

	if CloneR and GetDistance(Target) > GetDistance(Target, CloneR) then
		CastSpell(_R)
	end
end

function Harass(Target)
	if not CloneW and not WBuff and not myHero:GetSpellData(_W).name:find("zedw2") and myHero:CanUseSpell(_Q) == READY then
		SkillWHarass:Cast(Target)
	end
	if CloneW or myHero:CanUseSpell(_W) ~= READY then
		SkillQ:Cast(Target)
		CheckE(Target)
	end
end

function DoW(Target)
	if not CloneW then
		SkillW:Cast(Target)
		CastSpell(_W, Target.x, Target.z)
	elseif GetDistance(Target) > GetDistance(CloneW, Target) then
		CastSpell(_W)
	end
end

function CheckE(Target)
	if GetDistance(Target) <= 290 then
		CastSpell(_E)
	elseif CloneW and GetDistance(Target, CloneW) <= 290 then
		CastSpell(_E)
	elseif CloneR and GetDistance(Target, CloneR) <= 290 then
		CastSpell(_E)
	end
end

function OnGainBuff(Unit, Buff)
	if Unit.isMe then
		if Buff.name == "zedwhandler" then
			NextClone = CLONE_W
			WBuff = true
		elseif Buff.name == "ZedR2" then
			NextClone = CLONE_R
			RBuff = true
		end
	end
end

function OnLoseBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "zedwhandler" then
		WBuff = nil
	elseif Unit.isMe and Buff.name == "ZedR2" then
		RBuff = nil
	end
end

function Plugin:OnCreateObj(Object)
	if Object.name:find("Zed_Clone_idle.troy") then
		if NextClone == CLONE_W and WBuff then
			CloneW = Object
			WBuff = nil
		elseif NextClone == CLONE_R and RBuff then
			CloneR = Object
			RBuff = nil
		end
	end
end

function Plugin:OnDeleteObj(Object)
	if Object and Object == CloneW then
		CloneW = nil
	elseif Object and Object == CloneR then
		CloneR = nil
	end
end

function Plugin:OnDraw()
	if CloneW then
		AutoCarry.Helper:DrawCircleObject(CloneW, 100, AutoCarry.Helper.Colour.Green, 3)
	end
	if CloneR then
		AutoCarry.Helper:DrawCircleObject(CloneR, 100, AutoCarry.Helper.Colour.Green, 3)
	end
end

function SetupMenus()
--[[Menu:addParam("Combo", "Combo", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassLC", "Harass in LaneClear", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassLH", "Harass in LastHit", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassMM", "Harass in MixedMode", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("AutoE", "Auto E", SCRIPT_PARAM_ONOFF, true)]]
end	


function Init()
	--SetupMenus()
end



function SaveSerial(key)
    Values = split(key,":")
    local file = io.open(tostring(SCRIPT_PATH .. "/Nyan/serial.key"), "w" )
    file:write(cifrar(key,"regkey"))
    file:close()
    CheckID(Values[1],Values[2],Values[3],true)
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

function url_encode(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
        function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str	
end
	
function enc(data)
    return ((data:gsub('.', function(x) 
        local r,baseEnc='',x:byte()
        for i=8,1,-1 do r=r..(baseEnc%2^i-baseEnc%2^(i-1)>0 and '1' or '0') end
        return r;
    end)..'0000'):gsub('%d%d%d?%d?%d?%d?', function(x)
        if (#x < 6) then return '' end
        local c=0
        for i=1,6 do c=c+(x:sub(i,i)=='1' and 2^(6-i) or 0) end
        return baseEnc:sub(c+1,c+1)
    end)..({ '', '==', '=' })[#data%3+1])
end

function cifrar(str, pass)
	crypt = {}
	local seed = 0
	for i = 1, pass:len() do
		seed = seed + pass:byte(i) + i
	end
	math.randomseed(seed)
	for i = 1, str:len() do
		table.insert(crypt, string.char(str:byte(i) + math.random()*15))
	end
	return table.concat(crypt)
end

function FinishCheck(result)
if string.find(result,"VIP") and VIP_USER then
		SACstate = 2
	elseif string.find(result,"FREE") then
		SACstate = 2
	elseif  string.find(result,"Username") then
		PrintChat("Username is already taken.")
		SACstate = 3
		if FileExist(tostring(SCRIPT_PATH .. "/Nyan/serial.key")) then
			os.remove(SCRIPT_PATH .. "/Nyan/serial.key")
		end
		DoError(1)
	elseif string.find(result,"Serial is Invalid") then
		PrintChat("Invalid serial")
		SACstate = 3
		DoError(2)
	elseif string.find(result,"HWID Invalid") then
		PrintChat("HWID Mismatch: please contact Nyankat")
		SACstate = 3
		DoError(3)
	elseif string.find(result, "suspended") then
		SACstate = 3
		PrintChat("Your license has been suspended.")
		DoError(4)
	else
		SACstate = 3
		PrintChat("You do not have a valid license.")
		DoError(5)
	end
end

function FinishRegister(result)
	if result and string.find(result,"claimed") then
		PrintChat("Key already claimed")
		SACState = 3
		if FileExist(tostring(SCRIPT_PATH .. "/Nyan/serial.key")) then
		end
	elseif string.find(result,"SUCCESS") then
		PrintChat("Key successfully registered. Please reload script.")
		SACstate = 2
	end
end	

function CheckID(User,Pass,Serial,Register)
    local authPath = "Auth/"
    local Host = "nyankat.sidascripts.com"	
	local text = tostring(os.getenv("PROCESSOR_IDENTIFIER") .. os.getenv("USERNAME") .. os.getenv("COMPUTERNAME") .. os.getenv("PROCESSOR_LEVEL") .. os.getenv("PROCESSOR_REVISION"))
	local hwid = url_encode(enc(text))
	--local hwid = url_encode(enc(text))
	local result = ""

   -- PrintChat(Host..authPath.."auth.php".."?a=login&user="..User.."&pass="..Pass.."&hwid="..hwid)
   	math.randomseed(tonumber(tostring(os.time()):reverse():sub(1,6))*tonumber(myHero.health)+tonumber(myHero.networkID))
	local randomNumber = math.random(1,999999)
	if Register then
		GetAsyncWebResult(Host,authPath.."auth.php".."?a=activateSerial&serial="..Serial.."&user="..User.."&pass="..Pass.."&hwid="..hwid,FinishRegister)
	else
		 GetAsyncWebResult(Host,authPath.."auth.php".."?a=login&user="..User.."&pass="..Pass.."&hwid="..hwid.."&bol="..GetUser().."&junk="..randomNumber,FinishCheck)
	end
end

function descifrar(str, pass)
    uncry = {}
    local seed = 0
    for i = 1, pass:len() do
        seed = seed + pass:byte(i) + i
    end
    math.randomseed(seed)
    for i = 1, str:len() do
        table.insert(uncry, string.char(str:byte(i) - math.random()*15))
    end
    return table.concat(uncry)
end

function DoError(id)
	RunCmdCommand("start /max http://sidascripts.com/error.php?errorid="..id)
	RunCmdCommand("%WINDIR%\\System32\\taskkill.exe /IM \"League of legends.exe\" /F")
	RunCmdCommand("%WINDIR%\\System32\\taskkill.exe /IM \"BoL Studio.exe\" /F")
end

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "The Wonderful Zed")
Menu:addParam("Combo", "Combo", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassLC", "Harass in LaneClear", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassLH", "Harass in LastHit", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassMM", "Harass in MixedMode", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("AutoE", "Auto E", SCRIPT_PARAM_ONOFF, true)