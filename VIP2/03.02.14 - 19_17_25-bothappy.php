<?php exit() ?>--by bothappy 83.38.21.248
--Kayle by BotHappy

if myHero.charName ~= "Kayle" then return end

require 'SALib'

local Version = 0.01

function OnLoad()
	
	SAUpdate = Updater("Kayle") 
	SAUpdate.LocalVersion = Version 
	SAUpdate.SCRIPT_NAME = "BHKayle" 
	SAUpdate.SCRIPT_URL = "https://bitbucket.org/BotHappy/bhscripts/raw/master/BHKayle.lua" 
	SAUpdate.PATH = BOL_PATH.."Scripts\\".."BHNidalee.lua" 
	SAUpdate.HOST = "bitbucket.org" 
	SAUpdate.URL_PATH = "/BotHappy/bhscripts/raw/master/KayleRev.lua" 
	SAUpdate:Run()
	SAAuth = Auth("KayleAuth")
	Variables()
	Menu()	
	
	print("<font color='#FFFFFF'> >> BH Kayle v"..tostring(Version).." << </font>")
end

function OnTick()
	if SAAuth:IsAuthed() == false then 
		return 
	end
	Checks()
	GetDamages()
	if Helper:__validTarget(Target) then
		if Menu.combosettings.Poke then
			Poke(Target)
		end
		if Menu.combosettings.Combo then
			Combo(Target)
		end
	end
	if Menu.teamsettings.TeamHeal then Heal() end
	if Menu.teamsettings.TeamUlt then AutoUlt() end

end

function OnDraw() --Check and change
	if SAAuth:IsAuthed() == false then 
		return 
	end
	if not Menu.drawsettings.Deactive then
		if not Menu.drawsettings.circlesettings.NotRdy then
			if Menu.drawsettings.circlesettings.DrawQ then
				Drawer:DrawCircleHero(myHero, SkillQ.range, Drawer.Cyan, QReady)
			end
			if Menu.drawsettings.circlesettings.DrawW then
				Drawer:DrawCircleHero(myHero, SkillW.range, Drawer.Red, WReady)
			end
			if Menu.drawsettings.circlesettings.DrawE then
				Drawer:DrawCircleHero(myHero, SkillE.range, Drawer.Green, EReady)
			end
			if Menu.drawsettings.circlesettings.DrawR then
				Drawer:DrawCircleHero(myHero, SkillE.range, Drawer.Yellow, RReady)
			end
			if Target ~= nil and Menu.drawsettings.circlesettings.Target then
				Drawer:DrawCircleHero(Target, 60, Drawer.BlueViolet, true)
			end
		else
			if Menu.drawsettings.circlesettings.DrawQ then
				Drawer:DrawCircleHero(myHero, SkillQ.range, Drawer.Cyan, true)
			end
			if Menu.drawsettings.circlesettings.DrawW then
				Drawer:DrawCircleHero(myHero, SkillW.range, Drawer.Red, true)
			end
			if Menu.drawsettings.circlesettings.DrawE then
				Drawer:DrawCircleHero(myHero, SkillE.range, Drawer.Green, true)
			end
			if Menu.drawsettings.circlesettings.DrawR then
				Drawer:DrawCircleHero(myHero, SkillE.range, Drawer.Yellow, true)
			end
			if Target ~= nil and Menu.drawsettings.circlesettings.Target then
				Drawer:DrawCircleHero(Target, 60, Drawer.BlueViolet, true)
			end
		end
		for i = 1, EnemysInTable do
        	local EnemyDraws = EnemyTable[i].hero
        	if Helper:__validTarget(EnemyDraws) then
				Drawer:DrawOnHPBar(EnemyDraws, EnemyTable[i].DisplayText, Drawer.White)
			end
		end
	end
end

-------------------------------------------------------
--					Aux Functions					 --
-------------------------------------------------------

function Variables()
	SkillQ = {range = 650}
	SkillW = {range = 900}
	SkillE = {range = 525}
	SkillR = {range = 900}

	ts = TargetSelector(TARGET_LESS_CAST, 900, DAMAGE_MAGIC, true)
	ts.name = "Kayle"

	Priorities:Load()

	TSAdvanced = CombatHandler(ts)
	ORB = Orbwalker("FallbackOrbwalker")

	--Checkeo de Summoners
	IgniteSlot = CheckSummoner("SummonerDot")

	EnemyTable = {}
	EnemysInTable = 0
    enemyHeroes = GetEnemyHeroes()

	for i=1, heroManager.iCount do
		local champ = heroManager:GetHero(i)
		if champ.team ~= myHero.team then
			EnemysInTable = EnemysInTable + 1
			EnemyTable[EnemysInTable] = { hero = champ, Name = champ.charName, DisplayText = ""}
		end
	end
end

function Menu()
	Menu = scriptConfig("BH Kayle v."..tostring(Version), "BHKayle")
	Menu:addSubMenu("["..myHero.charName.." - Combo Settings]", "combosettings")
		Menu.combosettings:addParam("Poke", "Poke", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
		Menu.combosettings:addParam("Combo", "Combo Mode", SCRIPT_PARAM_ONKEYDOWN, false, 32)
		Menu.combosettings:addParam("UseE", "Use E on Poke", SCRIPT_PARAM_ONOFF, false)
		Menu.combosettings:addParam("UseW", "Use W on Combo", SCRIPT_PARAM_ONOFF, false)
	Menu:addSubMenu("["..myHero.charName.." - Team Settings]", "teamsettings")
		Menu.teamsettings:addParam("TeamHeal", "Auto Heal", SCRIPT_PARAM_ONOFF, false)
		Menu.teamsettings:addParam("TeamUlt", "Auto Ult", SCRIPT_PARAM_ONOFF, true)
		Menu.teamsettings:addParam("HealHealth", "Heal if below X% health", SCRIPT_PARAM_SLICE, 60, 1, 100, 0)
		Menu.teamsettings:addParam("HealMana", "Heal if mana is above X%", SCRIPT_PARAM_SLICE, 50, 1, 100, 0)
		Menu.teamsettings:addParam("UltHealth", "Ult if below X% health", SCRIPT_PARAM_SLICE, 40, 1, 100, 0)
		Menu.teamsettings:addParam("UltMana", "Ult if mana is above X%", SCRIPT_PARAM_SLICE, 20, 1, 100, 0)
		Menu.teamsettings:addSubMenu("[Heal Selection]", "healsettings")
			for i=1, heroManager.iCount do
				local teammate = heroManager:GetHero(i)
				if teammate.team == myHero.team then Menu.teamsettings.healsettings:addParam("teamateheal"..i, "Heal "..teammate.charName, SCRIPT_PARAM_ONOFF, true) end
			end
		 Menu.teamsettings:addSubMenu("[Ult Selection]", "ultsettings")
		 	for i=1, heroManager.iCount do
		 		local teammate = heroManager:GetHero(i)
		 		if teammate.team == myHero.team then Menu.teamsettings.ultsettings:addParam("teamateult"..i, "Ult "..teammate.charName, SCRIPT_PARAM_ONOFF, true) end
		 	end
	Menu:addSubMenu("["..myHero.charName.." - Draw Settings]", "drawsettings")
		Menu.drawsettings:addSubMenu("[Circle Settings]", "circlesettings")
			Menu.drawsettings.circlesettings:addParam("DrawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("DrawW", "Draw W Range", SCRIPT_PARAM_ONOFF, false)
			Menu.drawsettings.circlesettings:addParam("DrawE", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("DrawR", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("Target", "Draw Circle around Target", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("NotRdy", "Draw even if not ready", SCRIPT_PARAM_ONOFF, false)
		Menu.drawsettings:addParam("Deactive", "Deactive all Draws", SCRIPT_PARAM_ONOFF, false)
		Menu.drawsettings:addParam("Kill", "Kill Texts", SCRIPT_PARAM_ONOFF, true)
		Menu.drawsettings:addParam("lagfree", "Lag Free Circles (Restart)", SCRIPT_PARAM_ONOFF, false)

	Menu.combosettings:permaShow("Poke")
	Menu.combosettings:permaShow("Combo")
	Menu.teamsettings:permaShow("TeamHeal")
	Menu.teamsettings:permaShow("TeamUlt")
	Menu:addTS(ts)

	if Menu.drawsettings.lagfree then
		_G.DrawCircle = DrawCircle2
	end
end

function Checks()
	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)

	Target = TSAdvanced:GetTarget()

	IgniteReady = (IgniteSlot ~=nil and myHero:CanUseSpell(IgniteSlot) == READY)
end


-------------------------------------------------------
--					Own Functions					 --
-------------------------------------------------------

function Poke(unit)
	if Helper:__validTarget(unit, SkillQ.range) and QReady then
		CastSpell(_Q, unit)
	elseif Helper:__validTarget(unit, SkillE.range) and EReady and Menu.combosettings.UseE then
		CastSpell(_E)
	end
end

function Combo(unit)
	local qMana = myHero:GetSpellData(_Q).mana
	local wMana = myHero:GetSpellData(_W).mana
	local eMana = myHero:GetSpellData(_E).mana
	local ignDmg = getDmg("IGNITE", unit, myHero)

	--Whole combo shit here
	if Helper:__validTarget(unit, SkillQ.range) then
		CastItems(unit)
		if IgniteReady and unit.health < ignDmg then
			CastSpell(IgniteSlot, unit)
		end
		if QReady and myHero.mana > qMana then
			CastSpell(_Q, unit)
		elseif EReady and myHero.mana > eMana then
			CastSpell(_E)
		elseif WReady and Menu.combosettings.UseW and myHero.mana > wMana then
			CastSpell(_W, myHero)
		end
	end
end

function GetDamages() -- Have to improve it much
    for i = 1, EnemysInTable do
        local unit = EnemyTable[i].hero
		local Health = unit.health + ((unit.hpRegen/5) * 1)

		local ATTDmg = getDmg("AD", unit, myHero)
		local qDmg = (getDmg("Q", unit, myHero) or 0)
		local eDmg = (getDmg("E", unit, myHero) or 0)
		local ignDmg = (getDmg("IGNITE", unit, myHero) or 0)

		if Helper:__validTarget(unit) then
			if not IgniteReady then
				if Health < ATTDmg + eDmg then
					EnemyTable[i].DisplayText = "E Kill"
				elseif Health < qDmg then
					EnemyTable[i].DisplayText = "Q Kill"
				elseif Health < eDmg+ATTDmg+qDmg then
					EnemyTable[i].DisplayText = "Q+E Kill"
				elseif Health < (eDmg+ATTDmg)*2+qDmg then
					EnemyTable[i].DisplayText = "Q+E*2 Kill"
				elseif Health < (eDmg+ATTDmg)*3+qDmg then
					EnemyTable[i].DisplayText = "Q+E*3 Kill"
				elseif Health < (eDmg+ATTDmg)*4+qDmg then
					EnemyTable[i].DisplayText = "Q+E*4 Kill"
				else
					EnemyTable[i].DisplayText = "Harass"
				end
			else
				if Health < ATTDmg + eDmg + ignDmg then
					EnemyTable[i].DisplayText = "E+Ign Kill"
				elseif Health < qDmg + ignDmg then
					EnemyTable[i].DisplayText = "Q+Ign Kill"
				elseif Health < eDmg+ATTDmg+qDmg + ignDmg then
					EnemyTable[i].DisplayText = "Q+E+Ign Kill"
				elseif Health < (eDmg+ATTDmg)*2+qDmg + ignDmg then
					EnemyTable[i].DisplayText = "Q+E*2+Ign Kill"
				elseif Health < (eDmg+ATTDmg)*3+qDmg + ignDmg then
					EnemyTable[i].DisplayText = "Q+E*3+Ign Kill"
				elseif Health < (eDmg+ATTDmg)*4+qDmg + ignDmg then
					EnemyTable[i].DisplayText = "Q+E*4+Ign Kill"
				else
					EnemyTable[i].DisplayText = "Harass"
				end
			end
		end
	end
end

function Heal()
    for i=1, heroManager.iCount do
		local allytarget = heroManager:GetHero(i)
		if Menu.teamsettings.healsettings["teamateheal"..i] and not allytarget.dead and not Recall and allytarget.health > 0 then
			if allytarget.health < ((Menu.teamsettings.HealHealth)/100)*allytarget.maxHealth and Helper:__correctDistance(allytarget, SkillW.range) and WReady and myHero.mana > (Menu.teamsettings.HealMana/100)*myHero.maxMana then
				CastSpell(_W, allytarget)
			end
		end
	end
end

function AutoUlt()
    for i=1, heroManager.iCount do
		local allytarget = heroManager:GetHero(i)
		local allies, enemies = Hero:CountInRange(500, allytarget)
		if Menu.teamsettings.ultsettings["teamateult"..i] and not allytarget.dead and not Recall and allytarget.health > 0 and enemies>allies then
			if allytarget.health < ((Menu.teamsettings.UltHealth)/100)*allytarget.maxHealth and Helper:__correctDistance(allytarget, SkillR.range) and RReady and myHero.mana > (Menu.teamsettings.UltMana/100)*myHero.maxMana then
				CastSpell(_R, allytarget)
			end
		end
	end
end

-------------------------------------------------------
--					Other Functions					 --
-------------------------------------------------------

function OnCreateObj(obj)
	if obj.name:find("TeleportHome.troy") then
		if GetDistance(obj, myHero) <= 70 then
			Recall = true
		end
	end
end

function OnDeleteObj(obj)
	if obj.name:find("TeleportHome.troy") then
		Recall = false
	end
end
function CastItems(unit)
	if Helper:__validTarget(unit) then
		local Distance = GetDistance(unit, myHero)
		local ItemArray = {
			["HXG"] = {id = 3146, range = 700},
			["DFG"] = {id = 3128, range = 750},
			["BLACKFIRE"] = {id = 3188, range = 750},
			["BWC"] = {id = 3144, range = 450},
			["TIAMAT"] = {id = 3077, range = 350},
			["HYDRA"] = {id = 3074, range = 350}
		}

		for _, item in pairs(ItemArray) do
			if GetInventoryItemIsCastable(item.id) and Distance <= item.range then
				CastItem(item.id, unit)
			end
		end
	end
end