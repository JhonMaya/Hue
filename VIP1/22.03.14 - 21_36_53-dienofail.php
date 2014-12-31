<?php exit() ?>--by dienofail 68.48.159.9
--[[ Rumble in the Jungle by BurnSupport

Thanks Burn.

Changelog :
   0.1 - Initial Release
]]--
require "VPrediction"
if myHero.charName ~= "Rumble" or not VIP_USER then return end

local VERSION = "0.1"
local Menu = nil
local VP = VPrediction()
local SkillE = {Width = 90, Speed = 1200, Delay= 0.450}
local SkillQ = {Width = 500, Speed = 5000, Delay= 0.450}

local lastAttack = 0
local lastAttackCD = 0
local lastWindUpTime = 0

--Honda7
local autoupdateenabled = true
-- https://www.dropbox.com/s/o0l38thvqf7q3j0/RumbleInTheJungle.lua
local UPDATE_SCRIPT_NAME = "RumbleInTheJungle"
local UPDATE_HOST = "dl.dropboxusercontent.com"
local UPDATE_PATH = "/s/o0l38thvqf7q3j0/RumbleInTheJungle.lua"
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

local ServerData
if autoupdateenabled then
	GetAsyncWebResult(UPDATE_HOST, UPDATE_PATH.."?rand="..math.random(1,1000), function(d) ServerData = d end)
	function update()
		if ServerData ~= nil then
			local ServerVersion
			local send, tmp, sstart = nil, string.find(ServerData, "local VERSION = \"")
			if sstart then
				send, tmp = string.find(ServerData, "\"", sstart+1)
			end
			if send then
				ServerVersion = tonumber(string.sub(ServerData, sstart+1, send-1))
			end

			if ServerVersion ~= nil and tonumber(ServerVersion) ~= nil and tonumber(ServerVersion) > tonumber(VERSION) then
				DownloadFile(UPDATE_URL.."?rand="..math.random(1,1000), UPDATE_FILE_PATH, function () print("<font color=\"#FF0000\"><b>"..UPDATE_SCRIPT_NAME..":</b> successfully updated. Reload (double F9) Please. ("..VERSION.." => "..ServerVersion..")</font>") end)     
			elseif ServerVersion then
				print("<font color=\"#FF0000\"><b>"..UPDATE_SCRIPT_NAME..":</b> You have got the latest version: <u><b>"..ServerVersion.."</b></u></font>")
			end		
			ServerData = nil
		end
	end
	AddTickCallback(update)
end
--end Honda7

function OnLoad()
	LoadMenu()
	loadMain()
	PrintChat("<font color='#0000FF'> >> Rumble in the Jungle"..VERSION.." Loaded! <<</font>")
end

function OnTick()
        Checks()
		target = GetCustomTarget()
		QTarget = TargetSelector.target
		DamageCalcs()
		if Menu.ks.killsteal then SmartKS() end
		if Menu.combo and target ~= nil then
			Combo(target)
		elseif  Menu.combo and Qtarget ~= nil then
			Combo(Qtarget)
		end
		if target ~= nil and GetDistance(target) < 600 and ValidTarget(target, 600) and Menu.combos.orb and Menu.combo then
				OrbWalking(target)
			elseif Menu.combos.orb and Menu.combo then
				moveToCursor()
			end

		
	end
		
	function OnProcessSpell(unit, spell)
    if unit == myHero then
        if spell.name:lower():find("attack") then
            lastAttack = GetTickCount() - GetLatency()/2
            lastWindUpTime = spell.windUpTime*1000
            lastAttackCD = spell.animationTime*1000
        end
    end
end

function Combo()   
                if EREADY and Menu.combos.useE and GetDistance(target) <= eRange then CastE(target) end
                if QREADY and Menu.combos.useQ and GetDistance(target) <= qRange then CastQ(target) end
	end


function SmartKS()
	local Enemies = GetEnemyHeroes()
	for i, enemy in pairs(Enemies) do
			if getDmg("Q", enemy, myHero) > enemy.health then
				CastQ(enemy)
			end

			if getDmg("E", enemy, myHero) > enemy.health then
				CastE(enemy)
		
		end
	end
	


function NeedHP()
        if myHero.health < (myHero.maxHealth * ( Menu.hp.HPHealth / 100)) then
                return true
        else
                return false
        end
end

function OnDraw()
	--> Ranges
	if not myHero.dead then
		if QREADY and Menu.draw.qDraw then
		DrawCircle3D(myHero.x, myHero.y, myHero.z, qRange, 1,  ARGB(255, 0, 255, 255))
		end
		if EREADY and Menu.draw.eDraw then
			DrawCircle3D(myHero.x, myHero.y, myHero.z, eRange, 1,  ARGB(255, 0, 255, 255))
		end
	end
	if Menu.draw.cDraw then
		for i=1, heroManager.iCount do
			local Unit = heroManager:GetHero(i)
			if ValidTarget(Unit) then
				if waittxt[i] == 1 and (KillText[i] ~= nil or 0 or 1) then
					PrintFloatText(Unit, 0, TextList[KillText[i]])
				end
			end
			if waittxt[i] == 1 then
				waittxt[i] = 30
			else
				waittxt[i] = waittxt[i]-1
			end
		end
	end
    end
	if Menu.draw.DrawTarget then
		if Target then
			DrawCircle(Target.x, Target.y, Target.z, 250, 0x7F006E)
		end
	end
			
end

function loadMain()
                hpReady, mpReady, fskReady = false, false, false
                qRange, eRange = 600, 850
				TextList = {"Harass", "Q Kill", "Q Kill", "Q+R Kill", "Q+E Kill", "Q+W+E+R Kill", "Q+W+Ex3+R Kill"}
				KillText = {}
				colorText = ARGB(255,0,255,0)
                
                
end

function DamageCalcs()
	for i=1, heroManager.iCount do
	local enemy = heroManager:GetHero(i)
		if ValidTarget(enemy) then
			dfgDmg, hxgDmg, bwcDmg, iDmg  = 0, 0, 0, 0
			qDmg, wDmg, rDmg, eDmg = 0, 0, 0, 0
			aDmg = getDmg("AD",enemy,myHero)
			if qReady then qDmg = getDmg("Q", enemy, myHero) end
			if wReady then wDmg = getDmg("W", enemy, myHero) end
			if rReady then rDmg = getDmg("R", enemy, myHero) end
			if eReady then eDmg = getDmg("E", enemy, myHero) end
			if dfgReady then dfgDmg = (dfgSlot and getDmg("DFG",enemy,myHero) or 0)	end
            if hxgReady then hxgDmg = (hxgSlot and getDmg("HXG",enemy,myHero) or 0) end
            if bwcReady then bwcDmg = (bwcSlot and getDmg("BWC",enemy,myHero) or 0) end
            if iReady then iDmg = (ignite and getDmg("IGNITE",enemy,myHero) or 0) end
            onspellDmg = (liandrysSlot and getDmg("LIANDRYS",enemy,myHero) or 0)+(blackfireSlot and getDmg("BLACKFIRE",enemy,myHero) or 0)
            extraDmg = dfgDmg + hxgDmg + bwcDmg + onspellDmg + iDmg
                   KillText[i] = 1
                  if enemy.health <= qDmg then
                          KillText[i] = 2
                  elseif enemy.health <= (qDmg + wDmg) and enemy.health > qDmg and wDmg then
                          KillText[i] = 3
                  elseif enemy.health <= (qDmg + rDmg) and enemy.health > qDmg and rDmg then
                          KillText[i] = 4
                  elseif enemy.health <= (qDmg + eDmg) and enemy.health > qDmg and eDmg then
                          KillText[i] = 5
                  elseif enemy.health <= (qDmg + wDmg + eDmg + rDmg) and enemy.health > qDmg and eDmg and wDmg and rDmg then
                          KillText[i] = 6
                  elseif enemy.health <= (qDmg + wDmg + eDmg*3 + rDmg) and enemy.health > qDmg and wDmg and eDmg and rDmg*3 then
                          KillText[i] = 7        
                   end        
        end
    end
end

 --[Certain Checks]--
function Checks()
        hpSlot, mpSlot, fskSlot = GetInventorySlotItem(2003),GetInventorySlotItem(2004),GetInventorySlotItem(2041)
        QREADY = (myHero:CanUseSpell(_Q) == READY)
        WREADY = (myHero:CanUseSpell(_W) == READY)
        EREADY = (myHero:CanUseSpell(_E) == READY)
        HPREADY = (hpSlot ~= nil and myHero:CanUseSpell(hpSlot) == READY)
        MPREADY =(mpSlot ~= nil and myHero:CanUseSpell(mpSlot) == READY)
        FSKREADY = (fskSlot ~= nil and myHero:CanUseSpell(fskSlot) == READY)
end
              
				 
				

function OrbwalkToPosition(position)
	if position ~= nil then
		if _G.AutoCarry.Orbwalker then
			_G.AutoCarry.Orbwalker:OverrideOrbwalkLocation(position)
		elseif _G.MMA_Loaded then 
			moveToCursor(position.x, position.z)
		end
	else
		if _G.AutoCarry.Orbwalker then
			_G.AutoCarry.Orbwalker:OverrideOrbwalkLocation(nil)
		elseif _G.MMA_Loaded then 
			moveToCursor()
		end
	end
end


function CastQ(Target)
	if QREADY and target ~= nil and ValidTarget(target, 1800) then
		 AOECastPosition, MainTargetHitChance, nTargets = VP:GetCircularAOECastPosition(target, SkillQ.Delay, SkillQ.Width, qRange, SkillQ.Speed, myHero)
		if MainTargetHitChance >= Menu.combos.qHit and nTargets >= Menu.combos.qTargets and GetDistance(AOECastPosition) < qRange then
		CastSpell(_Q, AOECastPosition.x,AOECastPosition.z)
		end
	end
end
function CastE(Target)
	if EREADY and target ~= nil and ValidTarget(target, 1800) then
		local CastPosition,  HitChance,  Position = VP:GetLineCastPosition(target, SkillE.Delay, SkillE.Width, eRange, SkillE.Speed, myHero, true)
		if HitChance >= Menu.combos.eHit and GetDistance(CastPosition) < eRange then
			CastSpell(_E, CastPosition.x, CastPosition.z)
		end
	end
end

function LoadMenu()
		Menu = scriptConfig("Rumble the Combokiller "..VERSION.."" , "Rumble") -- Main menu
		
		Menu:addParam("combo", "Full Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
		
		Menu:addSubMenu("Rumble - Combo Settings", "combos") -- Combo Submenu
		Menu.combos:addParam("useQ", "Use (Q) in Combo", SCRIPT_PARAM_ONOFF, true)
		Menu.combos:addParam("useE", "Use (E) in Combo", SCRIPT_PARAM_ONOFF, true)
		Menu.combos:addParam("qTargets", "Minimum Enemies to hit with (Q)", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
		Menu.combos:addParam("qHit", "Hitchance (Q)", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
		Menu.combos:addParam("eHit", "Hitchance (E)", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
		Menu.combos:addParam("orb", "No mma or sac? Use this. (Orbwalker)", SCRIPT_PARAM_ONOFF, false)
		Menu:permaShow("combo")
	
		Menu:addSubMenu("Rumble - Killsteal Settings", "ks")
		Menu.ks:addParam("killsteal", "Enable SmartKS", SCRIPT_PARAM_ONOFF, true)
		Menu.ks:permaShow("killsteal")
		--[[
		Menu:addSubMenu("Rumble - Ult Settings", "ult")
        Menu.ult:addParam("AutoUltimate", "Auto Ult (M)", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("M"))
        Menu.ult:addParam("MinimumHealth", "Minimum HP % for R", SCRIPT_PARAM_SLICE, 45, 1, 100, 0)
        Menu.ult:addParam("MinimumEnemies", "Minimum Enemies R", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
        Menu.ult:addParam("MinimumRange", "Minimum Range R", SCRIPT_PARAM_SLICE, 650, 200, 1000, -1)
		Menu.ult:permaShow("AutoUltimate")
		--]]
		
		Menu:addSubMenu("Rumble - Health Settings", "hp")	
		Menu.hp:addParam("aHP", "Auto HP Pots", SCRIPT_PARAM_ONOFF, true)
        Menu.hp:addParam("HPHealth", "Min % for Health Pots", SCRIPT_PARAM_SLICE, 50, 0, 100, 2)

		Menu:addSubMenu("Rumble - Drawing Settings", "draw")	
        Menu.draw:addParam("qDraw", "Draw (Q)", SCRIPT_PARAM_ONOFF, true)
        Menu.draw:addParam("eDraw", "Draw (E)", SCRIPT_PARAM_ONOFF, true)
        Menu.draw:addParam("DrawTarget", "Draw Target", SCRIPT_PARAM_ONOFF, true)
        Menu.draw:addParam("cDraw", "Draw Target Text", SCRIPT_PARAM_ONOFF, true)
				
		TargetSelector = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1000, DAMAGE_MAGIC)
		TargetSelector.name = myHero.charName
		Menu:addTS(TargetSelector)
     
                end
 
 --Credit Trees
function GetCustomTarget()
	TargetSelector:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then return _G.MMA_Target end
    if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair and _G.AutoCarry.Attack_Crosshair.Target and _G.AutoCarry.Attack_Crosshair.Target.type == myHero.type then return _G.AutoCarry.Attack_Crosshair.Target end
    return TargetSelector.target
end
--End Credit Trees

 function ArrangePrioritys()
    for i, Target in pairs(TargetHeroes) do
        SetPriority(priorityTable.AD_Carry, Target, 1)
        SetPriority(priorityTable.AP, Target, 2)
        SetPriority(priorityTable.Support, Target, 3)
        SetPriority(priorityTable.Bruiser, Target, 4)
        SetPriority(priorityTable.Tank, Target, 5)
    end
end


function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
        end
    end
end


function OrbWalking(target)
    if TimeToAttack() and GetDistance(target) <= 565 then
        myHero:Attack(target)
    elseif heroCanMove() then
        moveToCursor()
    end
end
 
function TimeToAttack()
    return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end
 
function heroCanMove()
        return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end
 
function moveToCursor()
    if GetDistance(mousePos) then
        local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
        myHero:MoveTo(moveToPos.x, moveToPos.z)
    end        
end
 
function OnAnimation(unit,animationName)
    if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end 