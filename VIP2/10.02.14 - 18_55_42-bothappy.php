<?php exit() ?>--by bothappy 83.38.21.248
--Kayle by BotHappy

if myHero.charName ~= "Kayle" then return end

require "SALib"

local Version = 0.04

function OnLoad()
	
	SAUpdate = Updater("Kayle") 
	SAUpdate.LocalVersion = Version 
	SAUpdate.SCRIPT_NAME = "BHKayle" 
	SAUpdate.SCRIPT_URL = "https://bitbucket.org/BotHappy/bhscripts/raw/master/BHKayle.lua" 
	SAUpdate.PATH = BOL_PATH.."Scripts\\".."BHKayle.lua" 
	SAUpdate.HOST = "bitbucket.org" 
	SAUpdate.URL_PATH = "/BotHappy/bhscripts/raw/master/REV/KayleRev.lua" 
	SAUpdate:Run()
	SAAuth = Auth("KayleAuth")
	--scriptStart = os.clock()
	Variables()
	Menu()	
	
	print("<font color='#FFFFFF'> >> BH Kayle v"..tostring(Version).." << </font>")
end

function OnTick()
	--if os.clock() > scriptStart + 20 and not SAAuth:IsAuthed() then UnloadScript(debug.getinfo(2).source) end
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
	if Menu.kssettings.enable then AutoKS() end
	if Menu.othersettings.Jungle then JungleClear() end
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
	lastAttack = 0
	lastAttackCD = 0

	JungleMobs = {}
	JungleFocusMobs = {}
	MyMap = Map()

	if MyMap:TwistedTreeline() then --
		FocusJungleNames = {
		["TT_NWraith1.1.1"] = true,
		["TT_NGolem2.1.1"] = true,
		["TT_NWolf3.1.1"] = true,
		["TT_NWraith4.1.1"] = true,
		["TT_NGolem5.1.1"] = true,
		["TT_NWolf6.1.1"] = true,
		["TT_Spiderboss8.1.1"] = true,
							}
		
		JungleMobNames = {
        ["TT_NWraith21.1.2"] = true,
        ["TT_NWraith21.1.3"] = true,
        ["TT_NGolem22.1.2"] = true,
        ["TT_NWolf23.1.2"] = true,
        ["TT_NWolf23.1.3"] = true,
        ["TT_NWraith24.1.2"] = true,
        ["TT_NWraith24.1.3"] = true,
        ["TT_NGolem25.1.1"] = true,
        ["TT_NWolf26.1.2"] = true,
        ["TT_NWolf26.1.3"] = true,
						}
	else 
	JungleMobNames = { 
        ["Wolf8.1.2"] = true,
        ["Wolf8.1.3"] = true,
        ["YoungLizard7.1.2"] = true,
        ["YoungLizard7.1.3"] = true,
        ["LesserWraith9.1.3"] = true,
        ["LesserWraith9.1.2"] = true,
        ["LesserWraith9.1.4"] = true,
        ["YoungLizard10.1.2"] = true,
        ["YoungLizard10.1.3"] = true,
        ["SmallGolem11.1.1"] = true,
        ["Wolf2.1.2"] = true,
        ["Wolf2.1.3"] = true,
        ["YoungLizard1.1.2"] = true,
        ["YoungLizard1.1.3"] = true,
        ["LesserWraith3.1.3"] = true,
        ["LesserWraith3.1.2"] = true,
        ["LesserWraith3.1.4"] = true,
        ["YoungLizard4.1.2"] = true,
        ["YoungLizard4.1.3"] = true,
        ["SmallGolem5.1.1"] = true,
					}
	FocusJungleNames = {
        ["Dragon6.1.1"] = true,
        ["Worm12.1.1"] = true,
        ["GiantWolf8.1.1"] = true,
        ["AncientGolem7.1.1"] = true,
        ["Wraith9.1.1"] = true,
        ["LizardElder10.1.1"] = true,
        ["Golem11.1.2"] = true,
        ["GiantWolf2.1.1"] = true,
        ["AncientGolem1.1.1"] = true,
        ["Wraith3.1.1"] = true,
        ["LizardElder4.1.1"] = true,
        ["Golem5.1.2"] = true,
		["GreatWraith13.1.1"] = true,
		["GreatWraith14.1.1"] = true,
					}
	end
	for i = 0, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil then
			if FocusJungleNames[object.name] then
				table.insert(JungleFocusMobs, object)
			elseif JungleMobNames[object.name] then
				table.insert(JungleMobs, object)
			end
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
		Menu.teamsettings:addParam("HealMana", "Heal if mana is above X", SCRIPT_PARAM_SLICE, 300, 100, 4000, 0)
		Menu.teamsettings:addParam("UltHealth", "Ult if below X% health", SCRIPT_PARAM_SLICE, 40, 1, 100, 0)
		Menu.teamsettings:addParam("UltMana", "Ult if mana is above X", SCRIPT_PARAM_SLICE, 150, 100, 4000, 0)
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
	Menu:addSubMenu("["..myHero.charName.." - KS Settings]", "kssettings")
		Menu.kssettings:addParam("enable", "AutoKS", SCRIPT_PARAM_ONOFF, true)
		Menu.kssettings:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
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
	Menu:addSubMenu("["..myHero.charName.." - Other Settings]", "othersettings")
		Menu.othersettings:addParam("Jungle", "Jungle Clear", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
		Menu.othersettings:addParam("Attack", "Attack on Jungle Clear", SCRIPT_PARAM_ONOFF, true)

	Menu.combosettings:permaShow("Poke")
	Menu.combosettings:permaShow("Combo")
	Menu.teamsettings:permaShow("TeamHeal")
	Menu.teamsettings:permaShow("TeamUlt")
	Menu.othersettings:permaShow("Jungle")
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

function AutoKS()
	for i = 1, heroManager.iCount do
		local Enemy = heroManager:getHero(i)
		if QReady and Menu.kssettings.useQ and ValidTarget(Enemy, SkillQ.range) and Enemy.health+10 < getDmg("Q",Enemy,myHero) then
			CastSpell(_Q, Enemy)
		end
    end
end

function Combo(unit)
	local qMana = myHero:GetSpellData(_Q).mana
	local eMana = myHero:GetSpellData(_E).mana
	local ignDmg = getDmg("IGNITE", unit, myHero)

	--Whole combo shit here
	if Helper:__validTarget(unit, SkillQ.range) then
		CastItems(unit)
		if IgniteReady and unit.health < ignDmg and GetDistance(myHero, unit) > myHero.range then
			CastSpell(IgniteSlot, unit)
		end
		if QReady and myHero.mana > qMana then
			CastSpell(_Q, unit)
		elseif EReady and myHero.mana > eMana then
			CastSpell(_E)
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
			if allytarget.health < ((Menu.teamsettings.HealHealth)/100)*allytarget.maxHealth and Helper:__correctDistance(allytarget, SkillW.range) and WReady and myHero.mana > Menu.teamsettings.HealMana then
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
			if allytarget.health < ((Menu.teamsettings.UltHealth)/100)*allytarget.maxHealth and Helper:__correctDistance(allytarget, SkillR.range) and RReady and myHero.mana > Menu.teamsettings.UltMana then
				CastSpell(_R, allytarget)
			end
		end
	end
end

function JungleClear()
	local JungleMob = GetJungleMob()
	if ValidTarget(JungleMob) then
		if TimeToAttack() and Menu.othersettings.Attack then
			myHero:Attack(JungleMob)
		end
		if EReady then CastSpell(_E) end
	end
end

function GetJungleMob()
	for _, Mob in pairs(JungleFocusMobs) do
		if ValidTarget(Mob, SkillE.range) then return Mob end
	end
	for _, Mob in pairs(JungleMobs) do
		if ValidTarget(Mob, SkillE.range) then return Mob end
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
	if FocusJungleNames[obj.name] then
		table.insert(JungleFocusMobs, obj)
	elseif JungleMobNames[obj.name] then
        table.insert(JungleMobs, obj)
	end
end

function TimeToAttack()
	return (GetTickCount() + GetLatency()*0.5 > lastAttack + lastAttackCD)
end

function OnDeleteObj(obj)
	if obj.name:find("TeleportHome.troy") then
		Recall = false
	end
	for i, Mob in pairs(JungleMobs) do
		if obj.name == Mob.name then
			table.remove(JungleMobs, i)
		end
	end
	for i, Mob in pairs(JungleFocusMobs) do
		if obj.name == Mob.name then
			table.remove(JungleFocusMobs, i)
		end
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

function OnProcessSpell(Object,Spell)
	if Object == myHero then
		if Spell.name:lower():find("attack") then
			lastAttack = GetTickCount() - GetLatency()*0.5
			lastAttackCD = Spell.animationTime*1000
		end
	end
end

function OnLoseBuff(unit, buff)
	if unit ~= myHero and buff.name == "JudicatorReckoning" then
		local wMana = myHero:GetSpellData(_W).mana
		if WReady and Menu.combosettings.UseW and not Recall and myHero.mana > wMana and Menu.combosettings.Combo then
			CastSpell(_W, myHero)
		end
	end
end