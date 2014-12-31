<?php exit() ?>--by bothappy 83.38.21.248
--Nidalee by BotHappy

if myHero.charName ~= "Nidalee" then return end

require "SALib"

local Version = 0.01

function OnLoad()
	SAUpdate = Updater("NidaleeUpdate") 
	SAUpdate.LocalVersion = Version 
	SAUpdate.SCRIPT_NAME = "BHNidalee" 
	SAUpdate.SCRIPT_URL = "https://bitbucket.org/BotHappy/bhscripts/raw/master/BHNidalee.lua" 
	SAUpdate.PATH = BOL_PATH.."Scripts\\".."BHNidalee.lua" 
	SAUpdate.HOST = "bitbucket.org" 
	SAUpdate.URL_PATH = "/BotHappy/bhscripts/raw/master/NidaRev.lua" 
	SAUpdate:Run()
	SAAuth = Auth("NidaleeAuth")
	Variables()
	Menu()
	if Prodict.status == (0 or 2) then
		for i = 1, heroManager.iCount do
	       local hero = heroManager:GetHero(i)
	       if hero.team ~= myHero.team then
	          ProdQ:GetPredictionOnImmobile(hero, OnCombo)
	          ProdQ:GetPredictionAfterDash(hero, OnCombo)
	       end
	    end	
	end
	print("<font color='#FFFFFF'> >> BH Nidalee v"..tostring(Version).." << </font>")
end

function OnCombo(unit, pos)
	if QReady and ValidTarget(unit) and pos ~= nil then
   		if not ProdQCol:GetMinionCollision(myHero, pos) then 
   			CastSpell(_Q, pos.x, pos.z) 
   		end
	end
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
	else
		if Menu.combosettings.Poke and Menu.combosettings.MovePoke then
			MoveToCursor()
		end
	end
	if Menu.teamsettings.TeamHeal then Heal() end
	if Menu.othersettings.Pounce then PounceMouse() end
	if Menu.othersettings.HumanJumper then HumanJumper() end
end

function OnDraw()
	if SAAuth:IsAuthed() == false then 
		return 
	end
	if not Menu.drawsettings.Deactive then
		if not Menu.drawsettings.circlesettings.NotRdy then
			if HumanForm then
				if Menu.drawsettings.circlesettings.DrawQ then
					Drawer:DrawCircleHero(myHero, SkillQ.range, Drawer.Cyan, QReady)
				end
				if Menu.drawsettings.circlesettings.DrawW then
					Drawer:DrawCircleHero(myHero, SkillW.range, Drawer.Red, WReady)
				end
				if Menu.drawsettings.circlesettings.DrawE then
					Drawer:DrawCircleHero(myHero, SkillE.range, Drawer.Green, EReady)
				end
				if Target ~= nil and Menu.drawsettings.circlesettings.Target then
					Drawer:DrawCircleHero(Target, 60, Drawer.BlueViolet, true)
				end
			elseif Menu.drawsettings.circlesettings.Cougar then
				Drawer:DrawCircleHero(myHero, 375, Drawer.Red, WMReady)
				Drawer:DrawCircleHero(myHero, 300, Drawer.Green, EMReady)
			end
		else
			if HumanForm then
				if Menu.drawsettings.circlesettings.DrawQ then
					Drawer:DrawCircleHero(myHero, SkillQ.range, Drawer.Cyan, true)
				end
				if Menu.drawsettings.circlesettings.DrawW then
					Drawer:DrawCircleHero(myHero, SkillW.range, Drawer.Red, true)
				end
				if Menu.drawsettings.circlesettings.DrawE then
					Drawer:DrawCircleHero(myHero, SkillE.range, Drawer.Green, true)
				end
				if Target ~= nil and Menu.drawsettings.circlesettings.Target then
					Drawer:DrawCircleHero(Target, 60, Drawer.BlueViolet, true)
				end
			elseif Menu.drawsettings.circlesettings.Cougar then
				Drawer:DrawCircleHero(myHero, 375, Drawer.Red, true)
				Drawer:DrawCircleHero(myHero, 300, Drawer.Green, true)
			end
		end
		for i = 1, EnemysInTable do
        	local EnemyDraws = EnemyTable[i].hero
        	if Helper:__validTarget(EnemyDraws) then
				Drawer:DrawOnHPBar(EnemyDraws, EnemyTable[i].SpearText, Drawer.White)
			end
		end
	end
end

-------------------------------------------------------
--					Aux Functions					 --
-------------------------------------------------------

function Variables()
	--Skills
	SkillQ = {range = 1700}
	SkillW = {range = 900}
	SkillE = {range = 600}

	--Inicializo Prodiction para la Q
	Prodict = ProdictManager.GetInstance()
	ProdQ = Prodict:AddProdictionObject(_Q, SkillQ.range, 1300, 0.125, 60)
	ProdQCol = Collision(SkillQ.range, 1300, 0.125, 60)

	--Target Selector
	ts = TargetSelector(TARGET_LESS_CAST, SkillQ.range, DAMAGE_MAGIC, true)
	ts.name = "Nidalee"

	Priorities:Load()

	--Inicio de Libreria
	TSAdvanced = CombatHandler(ts)
	ORB = Orbwalker("FallbackOrbwalker")

	--Checkeo de Summoners
	IgniteSlot = CheckSummoner("SummonerDot")

	--Inicializo las variables
	HumanForm = true

	EnemyTable = {}
	EnemysInTable = 0
    enemyHeroes = GetEnemyHeroes()

	Recall = false

	--Asigno a cada Enemigo un texto
	for i=1, heroManager.iCount do
		local champ = heroManager:GetHero(i)
		if champ.team ~= myHero.team then
			EnemysInTable = EnemysInTable + 1
			EnemyTable[EnemysInTable] = { hero = champ, Name = champ.charName, SpearText = "", DmgText = ""}
		end
	end
end

function Menu()
	Menu = scriptConfig("BH Nidalee v."..tostring(Version), "BHNida")
	Menu:addSubMenu("["..myHero.charName.." - Combo Settings]", "combosettings")
		Menu.combosettings:addParam("Poke", "Poke", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
		Menu.combosettings:addParam("Combo", "Combo Mode", SCRIPT_PARAM_ONKEYDOWN, false, 32)
		Menu.combosettings:addParam("Transform", "Transform on Combo Mode", SCRIPT_PARAM_ONOFF, true)
		Menu.combosettings:addParam("AutoCougar", "AutoCougar at X Range", SCRIPT_PARAM_SLICE, 400, 300, 550, 0)
		Menu.combosettings:addParam("AutoHuman", "AutoHuman at X Range", SCRIPT_PARAM_SLICE, 650, 500, 900, 0)
		Menu.combosettings:addParam("UseE", "Use E on Human Combo", SCRIPT_PARAM_ONOFF, false)
		Menu.combosettings:addParam("MovePoke", "Move on Poke", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("["..myHero.charName.." - Team Settings]", "teamsettings")
		Menu.teamsettings:addParam("TeamHeal", "Auto Heal", SCRIPT_PARAM_ONOFF, false)
		Menu.teamsettings:addParam("HealHealth", "Heal if below X% health", SCRIPT_PARAM_SLICE, 60, 1, 100, 0)
		Menu.teamsettings:addParam("HealMana", "Heal if mana is above X%", SCRIPT_PARAM_SLICE, 50, 1, 100, 0)
		--Menu.teamsettings:addParam("ForceHeal", "Force Heal on Cougar", SCRIPT_PARAM_ONOFF, false)
		Menu.teamsettings:addSubMenu("[Heal Selection]", "healsettings")
			for i=1, heroManager.iCount do
				local teammate = heroManager:GetHero(i)
				if teammate.team == myHero.team then Menu.teamsettings.healsettings:addParam("teamateheal"..i, "Heal "..teammate.charName, SCRIPT_PARAM_ONOFF, true) end
			end
	Menu:addSubMenu("["..myHero.charName.." - Draw Settings]", "drawsettings")
		Menu.drawsettings:addSubMenu("[Circle Settings]", "circlesettings")
			Menu.drawsettings.circlesettings:addParam("DrawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("DrawW", "Draw W Range", SCRIPT_PARAM_ONOFF, false)
			Menu.drawsettings.circlesettings:addParam("DrawE", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("Cougar", "Draw Cougar Ranges", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("Target", "Draw Circle around Target", SCRIPT_PARAM_ONOFF, true)
			Menu.drawsettings.circlesettings:addParam("NotRdy", "Draw even if not ready", SCRIPT_PARAM_ONOFF, false)
		Menu.drawsettings:addParam("Deactive", "Deactive all Draws", SCRIPT_PARAM_ONOFF, false)
		Menu.drawsettings:addParam("Kill", "Kill Texts", SCRIPT_PARAM_ONOFF, true)
		Menu.drawsettings:addParam("lagfree", "Lag Free Circles (Restart)", SCRIPT_PARAM_ONOFF, false)
	Menu:addSubMenu("["..myHero.charName.." - Other Settings]", "othersettings")
		Menu.othersettings:addParam("Pounce", "Pounce To Mouse", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("G"))
		Menu.othersettings:addParam("HumanJumper", "Pounce To Human", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))

	Menu.combosettings:permaShow("Poke")
	Menu.combosettings:permaShow("Combo")
	Menu.teamsettings:permaShow("TeamHeal")
	Menu:addTS(ts)

	if Menu.drawsettings.lagfree then
		_G.DrawCircle = DrawCircle2
	end
end

function CastQ(unit, pos)
    if ValidTarget(unit) and pos ~= nil then
    	local willCollide = ProdQCol:GetMinionCollision(myHero, pos)
   		if not willCollide then CastSpell(_Q, pos.x, pos.z) end
    end
end

function Checks()
	HumanForm = (myHero:GetSpellData(_Q).name == "JavelinToss")

	QReady = (myHero:CanUseSpell(_Q) == READY and HumanForm)
	WReady = (myHero:CanUseSpell(_W) == READY and HumanForm)
	EReady = (myHero:CanUseSpell(_E) == READY and HumanForm)
	RReady = (myHero:CanUseSpell(_R) == READY)

	QMReady = (myHero:CanUseSpell(_Q) == READY and not HumanForm)
	WMReady = (myHero:CanUseSpell(_W) == READY and not HumanForm)
	EMReady = (myHero:CanUseSpell(_E) == READY and not HumanForm)

	IgniteReady = (IgniteSlot ~=nil and myHero:CanUseSpell(IgniteSlot) == READY)

	Target = TSAdvanced:GetTarget()
end

function MoveToCursor()
	if GetDistance(mousePos) > 1 or LastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		Packet('S_MOVE', {x = moveToPos.x, y = moveToPos.z}):send()
	end	
end

function OnAnimation(Unit,AnimationName)
	if Unit.isMe and LastAnimation ~= AnimationName then LastAnimation = AnimationName end
end

-------------------------------------------------------
--					Own Functions					 --
-------------------------------------------------------

function Heal()
    if HumanForm == false then return end
    for i=1, heroManager.iCount do
		local allytarget = heroManager:GetHero(i)
		if Menu.teamsettings.healsettings["teamateheal"..i] and not allytarget.dead and not Recall and allytarget.health > 0 then
			if allytarget.health < ((Menu.teamsettings.HealHealth)/100)*allytarget.maxHealth and Helper:__correctDistance(allytarget, SkillE.range) and myHero.mana > (Menu.teamsettings.HealMana/100)*myHero.maxMana then
				if HumanForm and EReady then
					CastSpell(_E, allytarget)
				-- elseif Menu.teamsettings.ForceHeal and RReady and not HumanForm then
				-- 	CastSpell(_R)
				-- 	CastSpell(_E, allytarget)
				end
			end
		end
	end
end

function Poke(unit)
	if Menu.combosettings.MovePoke then
		MoveToCursor()
	end
	if Helper:__validTarget(unit, SkillQ.range) and QReady then
		ProdQ:GetPredictionCallBack(unit, CastQ)
	end
end

--Función de daño de las spears--
--Lo que he hecho es una línea que va cambiando entre los 
--dos puntos, y pillar el punto justo donde están.

function SpearDmg(unit)
	local distance = GetDistance(unit)
	if distance < 525 then
		return getDmg("Q", unit, myHero)
	else
		local mindmg = 43.75*myHero:GetSpellData(_Q).level+11.25+0.65*myHero.ap
		local maxdmg = 109.375*myHero:GetSpellData(_Q).level+33.75+1.625*myHero.ap
		local percent = (distance-525)/975
		if percent > 1 then
			percent = 1
		elseif percent < 0 then
			percent = 0
		end
		local dmg = (mindmg*(1-percent)+maxdmg*(percent))

		return myHero:CalcMagicDamage(unit, dmg)
	end
end

--Combo. Bastante básico pero bueno.
function Combo(unit)
	local qMana = myHero:GetSpellData(_Q).mana
	local eMana = myHero:GetSpellData(_E).mana
	local ignDmg = getDmg("IGNITE", unit, myHero)

	if Helper:__validTarget(unit, SkillQ.range) then
		CastItems(unit)
		if IgniteReady and unit.health < ignDmg then
			CastSpell(IgniteSlot, unit)
		end
		if HumanForm then
			if QReady and myHero.mana > qMana then
				ProdQ:GetPredictionCallBack(unit, CastQ)
			end
			if RReady and Menu.combosettings.Transform and GetDistance(unit) < Menu.combosettings.AutoCougar then
				CastSpell(_R)
			end
		else
			if WMReady then
				Packet('S_MOVE', {type=2, x = unit.x, y = unit.z}):send()
				DelayAction(function() CastSpell(_W) end, 0.09)
			elseif EMReady then
				Packet('S_MOVE', {type=2, x = unit.x, y = unit.z}):send()
				DelayAction(function() CastSpell(_E) end, 0.09)
			elseif QMReady then
				CastSpell(_Q)
			end
			if RReady and Menu.combosettings.Transform and GetDistance(unit) > Menu.combosettings.AutoHuman then
				CastSpell(_R)
			end
		end
	end
end

function HumanJumper()
	if not HumanForm then
		if RReady and WMReady then
			Packet('S_MOVE', {type=2, x = mousePos.x, y = mousePos.z}):send()
			DelayAction(function()
				CastSpell(_W)
				CastSpell(_R)
				CastSpell(_Q)
				end, 0.08)
		end
	end
end

function PounceMouse()
	if WMReady then
	    Packet('S_MOVE', {type=2, x = mousePos.x, y = mousePos.z}):send()
		DelayAction(function() CastSpell(_W) end, 0.08)
	end
	-- Check for FindNearestNonWall in front of the champ
end

function GetDamages() -- Have to improve it much
    for i = 1, EnemysInTable do
        local unit = EnemyTable[i].hero
		local Health = unit.health + ((unit.hpRegen/5) * 1)

		local ATTDmg = getDmg("AD", unit, myHero)
		local qDmg = (SpearDmg(unit) or 0)
		local qmDmg = (getDmg("QM", unit, myHero) or 0)
		local wmDmg = (getDmg("WM", unit, myHero) or 0)
		local emDmg = (getDmg("EM", unit, myHero) or 0)

		if Helper:__validTarget(unit) then
			local numberspears = Health/qDmg
			EnemyTable[i].SpearText = "Spears: "..RoundUp(numberspears)
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

function FindNearestNonWall(x0, y0, z0, maxRadius, precision)
    local vec, radius = D3DXVECTOR3(x0, y0, z0), 1
    if not IsWall(vec) then return vec end
    x0, z0, maxRadius, precision = math.round(x0 / precision) * precision, math.round(z0 / precision) * precision, maxRadius and math.floor(maxRadius / precision) or math.huge, precision or 50
    local function checkP(x, y) 
        vec.x, vec.z = x0 + x * precision, z0 + y * precision 
        return not IsWall(vec) 
    end
    while radius <= maxRadius do
        if checkP(0, radius) or checkP(radius, 0) or checkP(0, -radius) or checkP(-radius, 0) then 
            return vec 
        end
        local f, x, y = 1 - radius, 0, radius
        while x < y - 1 do
            x = x + 1
            if f < 0 then 
                f = f + 1 + 2 * x
            else 
                y, f = y - 1, f + 1 + 2 * (x - y)
            end
            if checkP(x, y) or checkP(-x, y) or checkP(x, -y) or checkP(-x, -y) or 
               checkP(y, x) or checkP(-y, x) or checkP(y, -x) or checkP(-y, -x) then 
                return vec 
            end
        end
        radius = radius + 1
    end
end