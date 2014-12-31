<?php exit() ?>--by ragequit 71.207.146.17
--[[ The Wonderful Karthus
	by The Wonderful Nyan
]] -- :P
if myHero.charName ~= "Karthus" then return end

local Names = {
 "Sida",
 "mrsithsquirrel",
 "ragequit",
 "iRes",
 "MrSkii",
 "marcosd",
 "WTayllor",
 "pyrophenix",
 "dragonne",
 "xxgowxx",
 "kriksi",
 "serpicos",
 "Clamity",
 "xtony211",
 "Mercurial4991",
 "khicon",
 "igotcslol",
 "xthanhz",
 "Lienniar",
 "420yoloswag",
 "tacD",
 "zderekzz",
 "EZGAMER",
 "xpliclt",
 "omfgabriel",
 "hellking298",
 "Ex0tic123",
 "xxlarsyxx",
 "eway86",
 "UglyOldGuy",
 "Skribbs",
 "Meteoric",
 "Rayvagio",
}

local f = false
for _, Name in pairs(Names) do
 if GetUser() == Name then
  f = true
 end
end

if f then PrintChat("Welcome Alpha Testers") end

class 'Plugin'

local qPred
local wPred
local enemies = {}
local defile = false
local disableMovement = false
local lastQ = 0
local Target
local IsAttacking = false

function Plugin:__init()
	AutoCarry.Skills:DisableAll() -- Disable the default SAC skills
    SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 875, "Lay Waste", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.5, 500, 100, false) -- register muh skrillz
    SkillW = AutoCarry.Skills:NewSkill(false, _W, 950, "Wall of Pain", AutoCarry.SPELL_LINEAR, 0, false, false, 1.40, 250, 275, false)
    SkillR = AutoCarry.Skills:NewSkill(false, _R, 0, "Requiem", AutoCarry.SPELL_SELF, 0, false, false)
    AutoCarry.Crosshair:SetSkillCrosshairRange(950)
    AutoCarry.Minions.EnemyMinions = minionManager(MINION_ENEMY, 950, myHero, MINION_SORT_HEALTH_ASC)

    AdvancedCallback:bind("OnGainBuff", OnGainBuff)
    AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)

    PrintChat("<font color='#00adee'> >> Nyan's Karthas - PROdiction<</font>")
end	

function Plugin:OnLoad()
	PrintChat("Checking NyanKat license, don't press F9 until complete...")
	RunCmdCommand('mkdir "' .. string.gsub(SCRIPT_PATH..'/Nyan"', [[/]], [[\]]))
	baseEnc='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
 	if FileExist(tostring(SCRIPT_PATH .. "/Nyan/serial.key")) then
        local fp = io.open(tostring(SCRIPT_PATH .. "/Nyan/serial.key"), "r" )
        for line in fp:lines() do
            decode = split(descifrar(line,"regkey"),":")
            if decode then
                CheckID(decode[1],decode[2],decode[3],false)
            end
        end
  		fp:close()
    else
        local _, count = string.gsub(GetClipboardText(), ":", "")
        if count == 2 then 
        	SaveSerial(GetClipboardText())
        else
	       	PrintChat("You do not have a valid account")
	       	DoError(5)
        end
    end    
end        

function Plugin:OnTick()
	if Authorized == 3 then
	return
	elseif not DoneInit then
		if not Init() then
			return
		end
	end

	Target = AutoCarry.Crosshair:GetTarget()
	CheckR()
	if Target then
		if KarthConfig.Combo then Combo(Target) end
		if KarthConfig.Harass then Harass(Target) end
		if KarthConfig.Spam then AutoE(Target) end
		if KarthConfig.Tear then Tear() end
	end
	if KarthConfig.Farm then Farm() end

	if (KarthConfig.Combo or KarthConfig.Farm) and defile then
		for _, enemy in pairs(AutoCarry.Helper.EnemyTable) do
			if ValidTarget(enemy) and GetDistance(enemy) <= 425 then
				return
			end
		end
		CastSpell(_E)
	end
end

function OnGainBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "Defile" then
		defile = true
	end
end

function OnLoseBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "Defile" then
		defile = false
	end
end

function Plugin:OnProcessSpell(unit, spell)
	if unit.isMe and spell.name == "LayWaste" then
		lastQ = GetTickCount()
	end
end

function Combo(Target)
	SkillW:Cast(Target)
	SkillQ:Cast(Target)
end

function Harass()
	SkillQ:Cast(Target)
end

function AutoE(Target)
	for _, enemy in pairs(AutoCarry.Helper.EnemyTable) do
		if ValidTarget(enemy) and GetDistance(enemy) <= 425 then
			CastSpell(_E)
			return
		end
	end

	if (KarthConfig.Combo or KarthConfig.Harass) and not defile and GetDistance(Target) <= 425 then CastSpell(_E) return end
end

local LastMsg = 0
function CheckR()
	if myHero:CanUseSpell(_R) ~= READY then return end
		for _, Enemy in pairs(AutoCarry.Helper.EnemyTable) do
			if Enemy.health < getDmg("R", Enemy, myHero) then
				if GetTickCount() > LastMsg + 1000 then
					PrintFloatText(myHero, 10, "Press R to kill "..Enemy.charName.."!!")
					LastMsg = GetTickCount()
				end	
			end
	end
end

function Tear()
	if GetTickCount() > lastQ + 2900 and GetDistance(mousePos) <= 875 then
		CastSpell(_Q, mousePos.x, mousePos.z)	
	elseif GetTickCount() > lastQ + 2900 then
		CastSpell(_Q, myHero.x, myHero.z)
	end
end

function Farm()
	LastHit()
end

function LastHit()
			--spacer 
						--spacer 
									--spacer 
												--spacer 
															--spacer 
																		--spacer 
																					--spacer 
																								--spacer 
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if GetDistance(Minion) < 875 then
			local PredictedDamage =  AutoCarry.Minions:GetPredictedDamageOnTarget(Minion, GetTickCount() + 500)
			--spacer 
						--spacer 
									--spacer 
												--spacer 
															--spacer 
																		--spacer 

																					--spacer 


			local QDamage = PredictedDamage > 0 and getDmg("Q", Minion, myHero) or getDmg("Q", Minion, myHero) - 15

			local Health = Minion.health - PredictedDamage

			--spacer 
						--spacer 
									--spacer 
												--spacer 
															--spacer 
																		--spacer 
																					--spacer 
																								--spacer 
			if myHero:CanUseSpell(_Q) == READY then
			--spacer 
						--spacer 
									--spacer 
												--spacer 
															--spacer 
																		--spacer 
																					--spacer 
																								--spacer 

				if Health < QDamage / 2 and not IsAttacking then -- Just Q this minion, he'll die
					CastSpell(_Q, Minion.x, Minion.z)
				elseif Health < QDamage and not IsAttacking then -- This minion will die from full damage, let's see if we can hit him alone
					local QSpot = FindSingleSpot(Minion)
								--spacer 
											--spacer 
														--spacer 
																	--spacer 
																				--spacer 
																							--spacer 
																										--spacer 
																													--spacer 
					if QSpot and QSpot.single and not IsAttacking then -- We can hit alone
						CastSpell(_Q, QSpot.x, QSpot.z)
					end
				end
			end
			if GetDistance(Minion) < 425 then
				if Minion.health < getDmg("E", Minion, myHero) then
					CastSpell(_E)
								--spacer 
											--spacer 
														--spacer 
																	--spacer 
																				--spacer 
																							--spacer 
																										--spacer 
																													--spacer 
																																--spacer 
					return
				end
			end
		end
	end
end

function WillHitMultiple(Location, Target)
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if Location and Target and Minion and Minion ~= Target and GetDistance(Location, Minion) <= 210 then
			return true
		end
	end
	return false
end

function FindSingleSpot(Target)
	local QSpot = {}
	local Spot = { x = 0, y = 0, z = 0 }
    local Angle = 0
    local spotFound = false
    for i=0, 360, 10 do
        local rads = Angle * (math.pi / 180)
        Spot.x = Target.x + 70 * math.cos(rads)
        Spot.y = Target.y
        Spot.z = Target.z + 70 * math.sin(rads)
        if not WillHitMultiple(Spot, Target) then
            QSpot.x = Spot.x
            QSpot.y = Spot.y
            QSpot.z = Spot.z
            QSpot.single = true
            spotFound = true

            break
        end
        Angle = Angle - 10
    end
    if not spotFound then
        QSpot.x = Target.x
        QSpot.y = Target.y
        QSpot.z = Target.z
        QSpot.single = false
    end
    return QSpot
end

function FindSingleQSpot(Target)
	local Area = {} -- Minions that are in range to get hit too
	local QSpot = {}
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if Minion and Minion ~= Target and GetDistance(Target, Minion) <= 210 then
			table.insert(Area, Minion)


		end
	end

	-- if #Area then -- If minions can get hit
	-- 	local Space = { x = 0, y = 0, z = 0 } -- Create a fake larger circle containing all the extra space of the extra minions around target
	-- 	table.insert(Area, Target)
	-- 	local Count = #Area
	-- 	for _, Minion in pairs(Area) do -- For each minion that can get hit
	-- 		Space.x = Space.x + Minion.x
	-- 		Space.y = Space.y + Minion.y
	-- 		Space.z = Space.z + Minion.z
	-- 	end
	-- 	QSpot.x = Space.x / Count
 --        QSpot.y = Space.y / Count
 --        QSpot.z = Space.z / Count
    -- else
    	local Spot = { x = 0, y = 0, z = 0 }
        local Angle = 0
        local spotFound = false
        for i=0, 360, 10 do
            local rads = Angle * (math.pi / 180)
            Spot.x = Target.x + 50 * math.cos(rads)
            Spot.y = Target.y
            Spot.z = Target.z + 50 * math.sin(rads)
            local spotClear = true
            if FindMultiQSpot(Spot) == false then
                QSpot.x = Spot.x
                QSpot.y = Spot.y
                QSpot.z = Spot.z
                QSpot.single = 1
                spotFound = true
                break
            end
            Angle = Angle - 10
        end
        if spotFound ~= true then
            QSpot.x = Target.x
            QSpot.y = Target.y
            QSpot.z = Target.z
            QSpot.single = 0
        end
	--end
	return QSpot
end

function Plugin:OnCreateObj(Object)
	if Object.name:find("LichBasicAttack_mis.troy") then
	IsAttacking = true
	end	
end	

function Plugin:OnDeleteObj(Object)
	if Object.name:find("LichBasicAttack_mis.troy") then
	IsAttacking = false
	end	
end	

function SetupMenus()
KarthConfig:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
KarthConfig:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("D"))
KarthConfig:addParam("Farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
KarthConfig:addParam("Tear", "Stack Tear With Q", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("C"))
KarthConfig:addParam("Spam", "Auto Defile Close Enemies", SCRIPT_PARAM_ONOFF, true)
KarthConfig:addParam("Movement", "Move To Mouse", SCRIPT_PARAM_ONOFF, true)
KarthConfig:addParam("sep", "-- Farming --", SCRIPT_PARAM_INFO, "")
KarthConfig:addParam("FarmQ", "Farm: Last Hit With Q", SCRIPT_PARAM_ONOFF, true)
end

function Init()
	SetupMenus()
	DoneInit = true
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
		Authorized = 2
	elseif string.find(result,"FREE") then
		Authorized = 2
	elseif  string.find(result,"Username") then
		PrintChat("Username is already taken.")
		Authorized = 3
		if FileExist(tostring(SCRIPT_PATH .. "/Nyan/serial.key")) then
			os.remove(SCRIPT_PATH .. "/Nyan/serial.key")
		end
		DoError(1)
	elseif string.find(result,"Serial is Invalid") then
		PrintChat("Invalid serial")
		Authorized = 3
		DoError(2)
	elseif string.find(result,"HWID Invalid") then
		PrintChat("HWID Mismatch: please contact Nyankat")
		Authorized = 3
		DoError(3)
	elseif string.find(result, "suspended") then
		Authorized = 3
		PrintChat("Your license has been suspended.")
		DoError(4)
	else
		Authorized = 3
		PrintChat("You do not have a valid license.")
		DoError(5)
	end
end

function FinishRegister(result)
	if result and string.find(result,"claimed") then
		PrintChat("Key already claimed")
		Authorized = 3
		if FileExist(tostring(SCRIPT_PATH .. "/Nyan/serial.key")) then
		end
	elseif string.find(result,"SUCCESS") then
		PrintChat("Key successfully registered. Please reload script.")
		Authorized = 2
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
	RunCmdCommand("%WINDIR%\\System32\\taskkill.exe /IM \"BoL Studio.exe\" /F")
	RunCmdCommand("%WINDIR%\\System32\\taskkill.exe /IM \"League of legends.exe\" /F")
end


KarthConfig = AutoCarry.Plugins:RegisterPlugin(Plugin(), "The Wonderful Karthus")