<?php exit() ?>--by ragequit 71.207.146.17
if myHero.charName ~= "Zed" then return end

class 'Plugin'

SACstate = 3
local DoneInit = false
local Target
local SkillQ, SkillW
local NextClone = 0
local CLONE_W, CLONE_R = 1, 2
local CloneW, CloneR = nil, nil
local WCloneIncoming, RCloneIncoming = nil, nil
local IncomingClones = {}
local WBuff, RBuff = nil, nil
local QDamage = 0
local WDamage = 0
local EDamage = 0
local RDamage = 0
local AADamage = 0
local ShadowDamage = 0
local UltQDamage = 0
local BoRKDamage = 0
local Target = nil
local FoundUltTarget = false
local FoundMarkedTarget = false
local MarkedTarget = nil
local FoundKillStealTarget = false
local Qlevel = 0
local Wlevel = 0
local Elevel = 0
local Rlevel = 0
local Qmana = 0
local Wmana = 0
local Emana = 50
local Rmana = 0
local UseCombo1 = false
local UseCombo2 = false
local UseCombo3 = false
local UseCombo4 = false
local UseCombo5 = false
local UseCombo6 = false
local UseCombo7 = false



function Plugin:__init()
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 900, "Razor Shuriken", AutoCarry.SPELL_LINEAR, 0, false, false, 1.742, 235, 100, false)
	SkillW = AutoCarry.Skills:NewSkill(false, _W, 550, "Living Shadow", AutoCarry.SPELL_LINEAR, 0, false, false, 1.5, 200, 100, false)
	SkillWHarass = AutoCarry.Skills:NewSkill(false, _W, 900, "Living Shadow", AutoCarry.SPELL_LINEAR, 0, false, false, 1.5, 200, 100, false)
	AutoCarry.Crosshair:SetSkillCrosshairRange(900)

	AdvancedCallback:bind("OnGainBuff", OnGainBuff)
	AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)
end

function Plugin:OnTick()
 --if Target then	SkillQ:Cast(Target) end
--lets do some Dynamic Target selection shall we?--

if not FoundMarkedTarget then DynamicTargetSelection() end
DynmaicSpellLevel()
DynamicGetDamage()
ItemManager()
--end	

-- preform damages	
	if Target and ValidTarget(Target) then
		if AutoCarry.Keys.AutoCarry and Menu.Combo then
			Combo(Target)
		elseif AutoCarry.Keys.MixedMode and Menu3.Harass then
			Harass(Target)
		end
		if Menu.autoE then CheckE(Target) end
	end
	if AutoCarry.Keys.MixedMode then 
		SpellFarm()
	end	
--end damage stuff--

end

--function Combo(Target)
	--[[if not CloneR and not RBuff and myHero:GetSpellData(_R).name ~= "ZedR2" and myHero:CanUseSpell(_R) == READY then
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
	end]]
--end

function Harass(Target)
	if not CloneW and not WBuff and not myHero:GetSpellData(_W).name:find("zedw2") and myHero:CanUseSpell(_Q) == READY then
		if Menu3.WaitQ and myHero.mana >= Qmana + Wmana then
				SkillWHarass:Cast(Target)
		end
		if not Menu3.WaitQ then
				SkillWHarass:Cast(Target)
		end
	end
	if CloneW or myHero:CanUseSpell(_W) ~= READY then
		SkillQ:Cast(Target)
		CheckE(Target)
	end
		SkillQ:Cast(Target)
end


function Combo(Target)
 Combo1D = AADamage
 Combo2D = QDamage
 Combo3D = QDamage + ShadowDamage + AADamage 
 Combo4D = QDamage + ShadowDamage + EDamage + AADamage
 Combo5D = QDamage + ShadowDamage + EDamage + AADamage + BoRKDamage
 Combo6D = QDamage + ShadowDamage + EDamage + AADamage + BoRKDamage + RDamage + AADamage + 400 
 if UseCombo1 == true then end
 if UseCombo2 == true then SkillQ:Cast(Target) end
 if UseCombo3 == true then DoubleQ(Target) CheckE(Target) end
 if UseCombo4 == true then DoubleQ(Target) CheckE(Target) end
 if UseCombo5 == true then DoubleQ(Target) CheckE(Target) HandleBorkCast(Target) end  
 if UseCombo6 == true then DoubleQ(Target) CheckE(Target) HandleRCast(Target) HandleBorkRCast(Target) end
end

function HandleCombo1()
end

function HandleCombo2()
 	SkillQ:Cast(Target)
end

function HandleCombo3()
	DoubleQ(Target) 
	CheckE(Target)
end

function HandleCombo4()
	DoubleQ(Target) 
	CheckE(Target) 
	HandleBorkCast(Target)
end

function HandleCombo5()
	DoubleQ(Target) 
	CheckE(Target) 
	HandleBorkCast(Target) 
end

function HandleCombo6()
end

function HandleCombo7()
end	


function SpellFarm()
SpellFarmQ()
SpellFarmW()
SpellFarmE()
end	

function SpellFarmQ()
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if Menu5.FarmQ and not AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Minion) then
			if GetDistance(Minion) <= 900 and GetDistance(Minion) <= 300 or (GetDistance(Minion) <= 900 and not AutoCarry.Orbwalker:AttackReady() and myHero:CanUseSpell(_Q) ~= READY) then
				if Minion.health <= getDmg("Q", Minion, myHero) then
					CastSpell(_Q, Minion.x, Minion.z)
				end
			end	
		end
	end	
end

function SpellFarmW()
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if Menu5.FarmE and Menu5.FarmW then
			if not Menu5.FarmQ or myHero:CanUseSpell(_Q) ~= READY then
				if GetDistance(Minion) <= 600 and not CloneW and Minion.health <= getDmg("E", Minion, myHero) then
					PrintChat("We Should Cast W")
					if not CloneW and not WCloneIncoming then
						CastSpell(_W, Minion.x, Minion.z)
					end	
				end
			end
		end
	end			
end	

function SpellFarmE()
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do	
		if Menu5.FarmE then
			if GetDistance(Minion) <= 280 or GetDistance(Minion, CloneW) <= 280 then
				if Minion.health <= getDmg("E", Minion, myHero) then
					--if not AutoCarry.Orbwalker:AttackReady() then  --dont use for now shouldnt need 
						PrintChat("We should Cast E")
						CastSpell(_E)
					--end	
				end
			end
		end		
	end		 
end	

function HandleRCast()
	if not CloneR and not RBuff and myHero:GetSpellData(_R).name ~= "ZedR2" and myHero:CanUseSpell(_R) == READY then
		CastSpell(_R, Target)
	end
end

function AllSpellsReady()
	if myHero:CanUseSpell(_Q) == READY and myHero:CanUseSpell(_W) == READY and myHero:CanUseSpell(_E) == READY and myHero:CanUseSpell(_R) == READY then
	end
end	

function HandleBorkCast(Target)
	local Botrk = GetInventorySlotItem(3153)
	if Botrk then
		CastSpell(Botrk, Target)
	end	
end

function HandleBorkRCast(Target)
	if CloneR or myHero:CanUseSpell(_R) ~= READY then
		local Botrk = GetInventorySlotItem(3153)
		if Botrk then
			CastSpell(Botrk, Target)
		end
	end
end	

function HandleSwaps()
	if CloneR and GetDistance(Target) > GetDistance(Target, CloneR) and not AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target) then
		CastSpell(_R)
	end
	if CloneW and GetDistance(Target) > GetDistance(Target, CloneW) and not AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target) and not PositionUnderTower then
		CastSpell(_W)
	end
end	

function DoubleQ(Target)
	DoW(Target)
	if CloneW then
		SkillQ:Cast(Target)
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

function DynamicTargetSelection()
	if FoundMarkedTarget == true then 
		Target = FoundUltTarget
	else
		Target = AutoCarry.Crosshair:GetTarget()
	end
end	

function DynmaicSpellLevel()
	if myHero:GetSpellData(_Q).level == 1 then
		Qmana = 75
		elseif myHero:GetSpellData(_Q).level == 2 then
			Qmana = 70
		elseif myHero:GetSpellData(_Q).level == 3 then
			Qmana = 65
		elseif myHero:GetSpellData(_Q).level == 4 then
			Qmana = 60
		elseif myHero:GetSpellData(_Q).level == 5 then
			Qmana = 55
		else 
			Qmana = 0
	end	
		if myHero:GetSpellData(_W).level == 1 then
			Wmana = 40
		elseif myHero:GetSpellData(_W).level == 2 then
			Wmana = 35
		elseif myHero:GetSpellData(_W).level == 3 then
			Wmana = 30
		elseif myHero:GetSpellData(_W).level == 4 then
			Wmana = 25
		elseif myHero:GetSpellData(_W).level == 5 then
			Wmana = 20
		else 
			Wmana = 0
	end			
end	


function QReady()
	if myHero:CanUseSpell(_Q) == READY then
		return true 
	else
		return false
	end	
end	

function WReady()
	if myHero:CanUseSpell(_W) == READY then
		return true 
	else
		return false
	end		
end

function EReady()
	if myHero:CanUseSpell(_E) == READY then
		return true 
	else
		return false
	end		
end

function RReady()
	if myHero:CanUseSpell(_R) == READY then
		return true 
	else
		return false
	end		
end

function SetActiveCombo(ID)
	 UseCombo1 = (ID == 1 and true or false)
	 UseCombo2 = (ID == 2 and true or false)
	 UseCombo3 = (ID == 3 and true or false)
	 UseCombo4 = (ID == 4 and true or false)
	 UseCombo5 = (ID == 5 and true or false)
	 UseCombo6 = (ID == 6 and true or false)
	 UseCombo7 = (ID == 7 and true or false)
 end

 function SmartComboGen(Target)
	 Combo1D = AADamage
	 Combo2D = QDamage
	 Combo3D = QDamage + ShadowDamage + AADamage 
	 Combo4D = QDamage + ShadowDamage + EDamage + AADamage
	 Combo5D = QDamage + ShadowDamage + EDamage + AADamage + BoRKDamage
	 Combo6D = QDamage + ShadowDamage + EDamage + AADamage + BoRKDamage + RDamage + AADamage + 450 

 		if Combo1D >= Target.health then
 			SetActiveCombo(1)
			elseif Combo2D >= Target.health then
 				SetActiveCombo(2)
 				elseif Combo3D >= Target.health then
 					SetActiveCombo(3)
 					elseif Combo4D >= Target.health then
 						SetActiveCombo(4)
 						elseif Combo5D >= Target.health then
 							SetActiveCombo(5)
 							elseif Combo6D >= Target.health then
 								SetActiveCombo(6)
 								else 
 									SetActiveCombo(7)
 		end							
 end

function DynamicGetDamage()
  if Target and ValidTarget(Target) then
    if QReady and IsInQRange then QDamage = getDmg("Q", Target, myHero) else QDamage = 0 end
    if QReady and WReady and IsInWRange and IsInQRangeShadow then WDamage = getDmg("Q", Target, myHero) / 2 else WDamage = 0 end
    if EReady and (IsInERange or IsInERangeShadow) then EDamage = getDmg("E", Target, myHero) else EDamage = 0 end
    if RReady and IsInRRange then RDamage = getDmg("R", Target, myHero) else RDamage = 0 end
    if CloneR and IsInQRangeShadowUlt and QReady then UltQDamage = getDmg("Q", Target, myHero) / 2 else UltQDamage = 0 end 
    if CloneW and CloneR and QReady and IsInQRangeDoubleShadow then ShadowDamage = getDmg("Q", Target, myHero) / 2 + getDmg("Q", Target, myHero) / 2 elseif (CloneR and IsInQRangeShadowUlt) or (CloneW and IsInQRangeShadow) and QReady then ShadowDamage = getDmg("Q", Target, myHero) / 2 else ShadowDamage = 0 end
    if AutoCarry.Orbwalker:AttackReady() and AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target) then AADamage = getDmg("AD", Target, myHero) else ADDamage = 0 end 
    if BoRKReady and IsInBoRKRange then BoRKDamage = getDmg("RUINEDKING", Target, myHero) else BoRKDamage = 0 end
  end    
end 

function ItemManager()
	BoRK = GetInventorySlotItem(3153)
	BoRKReady = (BoRK ~= nil and myHero:CanUseSpell(BoRK) == READY)
	for _, Item in pairs(AutoCarry.Items.ItemList) do
		if Item.ID == 3153 then
			Item.Enabled = false
		end
	end
end    


function IsInAARange()
 if Target and ValidTarget(Target) then
        if AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target) then
            return true
                else
            return false
        end 
    end        
end   

function IsInQRange()
    if Target and ValidTarget(Target) then
        if GetDistance(Target, myHero) <= 900 then
            return true
                else
            return false
        end 
    end          
end 

function IsInQRangeShadow()
    if Target and ValidTarget(Target) then
        if GetDistance(myHero, Target) <= 900 and GetDistance(CloneW, Target) <= 900 then
            return true
                else
            return false
        end 
    end          
end 

function IsInQRangeShadowUlt()
    if Target and ValidTarget(Target) then
        if GetDistance(myHero, Target) <= 900 and GetDistance(CloneR, Target) <= 900 then
            return true
                else
            return false
        end 
    end          
end 

function IsInQRangeDoubleShadow()
    if Target and ValidTarget(Target) then
        if GetDistance(myHero, Target) <= 900 and GetDistance(CloneW, Target) <= 900 and GetDistance(CloneR, Target) <= 900 then
            return true
                else
            return false
        end 
    end          
end      

function IsInWRange()
 if Target and ValidTarget(Target) then
        if GetDistance(Target) <= 550 then
            return true
                else
            return false
        end 
    end       
end    

function IsInERange()
 if Target and ValidTarget(Target) then
        if GetDistance(Target) <= 290 then
            return true
                else
            return false
        end 
    end       
end

function IsInERangeShadow()
 if Target and ValidTarget(Target) then
        if GetDistance(Target, CloneW) <= 290 or GetDistance(Target, CloneR) then
            return true
                else
            return false
        end 
    end       
end     

function IsInRRange()
 if Target and ValidTarget(Target) then
        if GetDistance(Target) <= 625 then
            return true
                else
            return false
        end 
    end      
end 

function IsInBoRKRange()
    if Target and ValidTarget(Target) then
        if GetDistance(myHero, Target) <= 450 then
            return true
                else
            return false
        end 
    end          
end 

function PositionUnderTower(Pos)
    local ClosestTower = AutoCarry.Structures:GetClosestEnemyTower(Pos)
    if CloneW and ClosestTower and GetDistance(CloneW, ClosestTower) <= AutoCarry.Structures.TowerRange + 200 then
        return true 
    end
end

function GetEnemiesAroundPos(Pos)
    local Count = 0
    for _, Target in pairs(AutoCarry.Helper.EnemyTable) do
        if GetDistance(Target, CloneW) <= 800 then 
            Count = Count + 1
        end
    end
    return Count
end 

function Plugin:OnSendPacket(p)
	local packet = Packet(p)
	if packet:get('name') == 'S_CAST' then
		local Source = objManager:GetObjectByNetworkId(packet:get('sourceNetworkId'))
		if Source.isMe then
			local SpellID = packet:get('spellId')
			if SpellID == _W then
				if not CloneW and not WCloneIncoming then
					WCloneIncoming = true
				end
			elseif SpellID == _R then
				if not CloneR and not RCloneIncoming then
					RCloneIncoming = true
				end
			end
		end
	end
end

function OnGainBuff(Unit, Buff)
	-- if Unit.isMe then
	-- 	if Buff.name == "zedwhandler" then
	-- 		NextClone = CLONE_W
	-- 		WBuff = true
	-- 	elseif Buff.name == "ZedR2" then
	-- 		NextClone = CLONE_R
	-- 		RBuff = true
	-- 	end
	-- end
	-- if not Unit.isMe then
	-- 	if Buff.name == "zedulttargetmark" then
	-- 		FoundMarkedTarget = true
	-- 		MarkedTarget = Unit
	-- 	end
	-- end		
end

function OnLoseBuff(Unit, Buff)
	-- if Unit.isMe and Buff.name == "zedwhandler" then
	-- 	WBuff = nil
	-- elseif Unit.isMe and Buff.name == "ZedR2" then
	-- 	RBuff = nil
	-- end
	-- if not Unit.isMe then
	-- 	if Buff.name == "zedulttargetmark" then
	-- 		FoundMarkedTarget = false
	-- 		MarkedTarget = nil
	-- 	end
	-- end		
end

function Plugin:OnCreateObj(Object)	
	if Object.name:find("Zed_Clone_idle.troy") then
		if WCloneIncoming and not CloneW then
			CloneW = Object
		elseif RCloneIncoming and not CloneR then
			CloneR = Object
		end
	end
end

function Plugin:OnDeleteObj(Object)
	if Object and Object == CloneW then
		CloneW = nil
		WCloneIncoming = false
	elseif Object and Object == CloneR then
		CloneR = nil
		RCloneIncoming = false
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



--[[function SaveSerial(key)
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
end]]

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "The Wonderful Zed")
Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
Menu2 = scriptConfig("The Wonderful Zed Combo Config", "NyanCombo")
Menu2:addParam("Combo", "Combo In AutoCarry", SCRIPT_PARAM_ONOFF, true)
Menu2:addParam("AllIn", "All in on hard kill in AutoCarry", SCRIPT_PARAM_ONOFF, true)
Menu2:addParam("AntiW", "Anti W Swap Under Tower", SCRIPT_PARAM_ONOFF, true)
Menu3 = scriptConfig("The Wonderful Zed Harass Config", "NyanHarass")
Menu3:addParam("Harass", "Harass in MixedMode", SCRIPT_PARAM_ONOFF, true)
Menu3:addParam("WaitQ", "Always Wait for energy to double Q", SCRIPT_PARAM_ONOFF, false)
Menu3:addParam("AntiW", "Anti W Swap Under Tower", SCRIPT_PARAM_ONOFF, true)
Menu4 = scriptConfig("The Wonderful Zed KillSteal Config", "NyanKS")
Menu4:addParam("QKS", "Use Q to KS", SCRIPT_PARAM_ONOFF, true)
Menu4:addParam("WKS", "Use W in KS", SCRIPT_PARAM_ONOFF, true)
Menu4:addParam("EKS", "Use E to KS", SCRIPT_PARAM_ONOFF, true)
Menu4:addParam("BoRKKS", "Use BoRK to KS", SCRIPT_PARAM_ONOFF, true)
Menu5 = scriptConfig("The Wonderful Zed Farm Config", "NyanFarm")
Menu5:addParam("FarmQ", "Farm with Q", SCRIPT_PARAM_ONOFF, true)
Menu5:addParam("FarmE", "Farm with E", SCRIPT_PARAM_ONOFF, true)
Menu5:addParam("FarmW", "Use W and E to farm", SCRIPT_PARAM_ONOFF, true)
Menu6 = scriptConfig("The Wonderful Zed Misc Config", "NyanMisc")
Menu6:addParam("AutoE", "Auto E anything", SCRIPT_PARAM_ONOFF, true)