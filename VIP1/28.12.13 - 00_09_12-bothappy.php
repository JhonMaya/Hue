<?php exit() ?>--by bothappy 92.56.232.103
--[[PROHeimer by BotHappy (Reworked from Dongers!)

Changelog:
	v1.0 - Reworked Script:
		+Updated Prodiction
		+Updated and reworked combo
		+Improved Target Selector with Priorities
		+Added Kill Draws
		+Added new KS Abilities
		+Added ability to interrumpt channeled spells
		+Added free lag circles
		+Reworked Menu
		+Fixed many bugs there

TODO:
	Reread the code
	Add HeimerRReady to combo
	Join Combos]]

if myHero.charName ~= "Heimerdinger" or not VIP_USER then return end

require "Collision"
require "Prodiction"

function OnLoad()
	Variables()
	CheckIgnite()
	Menu()
	
	PrintChat("<font color='#FFFFFF'> >> PROHeimer v"..Version.." loaded << </font>")
end

function OnTick()
	Checks()
	AutoKS()
	-- if Menu.othersettings.magnet then
	-- 	MagneticQSpot()
	-- end
	if ValidTarget(ts.target) then
		if Menu.othersettings.W then
			MoveToCursor()
			if GetDistanceBBox(ts.target) < RangeW then
				CastW(ts.target)
			end
		end
		if Menu.othersettings.E then
			MoveToCursor()
			if GetDistanceBBox(ts.target) < RangeE then
				CastE(ts.target)
			end
		end
		-- if Menu.combosettings.DisCombo then
		-- 	CastDistanceCombo(ts.target)
		-- end
		if Menu.combosettings.combo then
			IntelligentCombo(ts.target)
		end
	elseif not ValidTarget(ts.target) then
		if Menu.combosettings.combo and Menu.combosettings.OrbWalk then
			MoveToCursor()
		end
		if Menu.othersettings.W or Menu.othersettings.E then
			MoveToCursor()
		end
	end
end

function OnDraw()
	if Menu.drawsettings.drawQ then
        if QReady then
            DrawCircle(myHero.x, myHero.y, myHero.z, RangeQ, ARGB(255,127,0,110))
        end
    end
    if Menu.drawsettings.drawW then
        if WReady then
            DrawCircle(myHero.x, myHero.y, myHero.z, RangeW, ARGB(255,95,159,159))
        end
    end
    if Menu.drawsettings.drawE then
        if EReady then
            DrawCircle(myHero.x, myHero.y, myHero.z, RangeE, ARGB(255,204,50,50))
        end
    end
	if Menu.drawsettings.circles then
		if ValidTarget(ts.target, RangeRE+150) then DrawCircle(ts.target.x, ts.target.y, ts.target.z, 35, ARGB(255,198,239,247)) end
	end
	--Obsolete code, not working anymore that glitch
	-- if Menu.drawsettings.Dragon and gameState.map.shortName == "summonerRift" then
	-- 	DrawCircle(10117.65 , -61.54, 4804.69, 40, 0xFFFFFF) --Top One
	-- 	DrawCircle(10330.71 , -62.09, 4567.51, 40, 0xFFFFFF) --Mid One
	-- 	DrawCircle(10295 , -61.17, 4330.39, 40, 0xFFFFFF) --Bottom One??
	-- 	--DrawCircle(10254.695312, -60.704513, 4303.810546, 40, 0xFFFFFF) --Could work as bottom one

	-- 	DrawCircle(9595, -59.20, 5102.39, 40, 0x32cd32)
	-- 	DrawCircle(9532, -58.07, 5125.13, 40, 0x32cd32)
	-- 	DrawCircle(9272, -58.45, 5011.17, 40, 0x32cd32)
	-- end
	--Specific Draws here
	if Menu.drawsettings.texts then
		KillDraws()
	end
end

------------------------------------------------------
--					Self+Combo Functions			--
------------------------------------------------------

-- function MagneticQSpot()
-- 	for i, turretspot in pairs(TurretSpots) do
-- 		if GetDistance(mousePos, turretspot) <= 40 and QReady then
-- 			CastSpell(_Q, turretspot.x, turretspot.z)
-- 		end
-- 	end
-- end

function CastW(Unit)
	if WSpellPos ~= nil then
		local willCollide = ProdWCol:GetMinionCollision(WSpellPos, myHero)
   		if not willCollide then CastSpell(_W, WSpellPos.x, WSpellPos.z) end
   	end
end

function CastE(Unit)
	if ESpellPos ~= nil then
        CastSpell(_E, ESpellPos.x, ESpellPos.z)
    end
end

-- function CastDistanceCombo(Target)
-- 	if RReady and Menu.combosettings.UseR then
-- 		if EReady and DistanceToHit(Target) > RangeW and DistanceToHit(Target) <RangeRE and myHero.mana >= RMana then
-- 			CastSpell(_R)
-- 			CastE(Target)
-- 			ERCastTime = GetTickCount() + 150
-- 		end
-- 	end
-- 	if WReady and Menu.combosettings.UseW and (not Target.canMove or UnitSlowed) and GetTickCount() > ERCastTime and myHero.mana >= WMana and DistanceToHit(Target) < RangeW then
-- 		CastW(Target)
-- 	end
	
-- end

function IntelligentCombo(Target)
	UseItems(Target)
	if Menu.combosettings.OrbWalk then OrbWalking(Target) end
	local ECastTime = 0
	
	if EReady and DistanceToHit(Target) <RangeE and myHero.mana >= EMana and Menu.combosettings.UseE then
		CastE(Target)
		ECastTime = GetTickCount() + 150
	end

	if (GetTickCount() > ECastTime and (not Target.canMove or UnitSlowed)) or (not EReady or not Menu.combosettings.UseE or myHero.mana <= EMana) then
		if DistanceToHit(Target) < RangeQ then
			if QReady and RReady and Menu.combosettings.UseR and myHero.mana >= RMana then
				CastSpell(_R)
				CastSpell(_Q, Target.x, Target.z)
			end
			if QReady and GetDistance(Target) < RangeQ then
				CastSpell(_Q, Target.x, Target.z)
			end
			if WReady then
				CastW(Target)
			end
		elseif DistanceToHit(Target) > RangeQ then
			local minioncollision = ProdWCol:GetMinionCollision(WSpellPos, myHero)
			if not minioncollision then
				CastSpell(_R)
				CastSpell(_W, WSpellPos.x, WSpellPos.z)
			end
		end
	end
	if IgniteReady and Menu.combosettings.UseIgnite then
		if Target.health < (50 + (20 * myHero.level)) and ValidTarget(Target,600) then
			CastSpell(IgniteSlot, Target)
		end
	end
end


------------------------------------------------------
--					KS Functions					--
------------------------------------------------------

function AutoKS()
	--add option toggle on menu
	if Menu.othersettings.KSIgnite then AutoIgniteKS() end
	if Menu.othersettings.KSW then AutoWKS() end
	if Menu.othersettings.KSE then AutoEKS() end
end

function AutoIgniteKS()
	IgniteDMG = 50 + (20 * myHero.level)
	for i = 1, heroManager.iCount do
        local Enemy = heroManager:getHero(i)
		if ValidTarget(Enemy, 600) and Enemy.health <= IgniteDMG then
			CastSpell(IgniteSlot, Enemy)
		end
	end
end

function AutoWKS()
    for i = 1, heroManager.iCount do
        local Enemy = heroManager:getHero(i)
        if WReady and ValidTarget(Enemy, RangeW) and Enemy.health < getDmg("W",Enemy,myHero) + 25 then
            CastW(Enemy)
        end
    end
end

function AutoEKS()
    for i = 1, heroManager.iCount do
        local Enemy = heroManager:getHero(i)
        if EReady and ValidTarget(Enemy, RangeE) and Enemy.health < getDmg("E",Enemy,myHero) + 25 then
            CastE(Enemy)
        end
    end
end

------------------------------------------------------
--					Auxiliar Functions				--
------------------------------------------------------

-- Damage Calculations --
function DamageCalcs()
	for i=1, heroManager.iCount do
	local enemy = heroManager:GetHero(i)
		if ValidTarget(enemy) then
			q1Dmg, q2Dmg, wDmg, eDmg, rq1Dmg, rq2Dmg, rwDmg = 0,0,0,0,0,0,0
			dfgDmg, iDmg  = 0, 0
			aDmg = getDmg("AD",enemy,myHero)
			if QReady then 
				q1Dmg = getDmg("Q", enemy, myHero, 1)
				q2Dmg = getDmg("Q", enemy, myHero, 2)
				turretdmg = q1Dmg*3 + q2Dmg
			else turretdmg = 0
			end
			if WReady then 
				wDmg = getDmg("W", enemy, myHero) 
			else
				wDmg = 0
			end
			if EReady then 
				eDmg = getDmg("E", enemy, myHero)
			else
				eDmg = 0
			end
			if RReady then 
				rq1Dmg = getDmg("R", enemy, myHero,1)
				rq2Dmg = getDmg("Q", enemy, myHero,3)
				rwDmg = getDmg("R", enemy, myHero,2)
				--reDmg = getDmg("R", enemy, myHero,3)
				turretupdmg = rq1Dmg*3 + rq2Dmg
				missiledmg = rwDmg+0.8*rwDmg+0.6*rwDmg
			else
				turretupdmg = 0
				missiledmg = 0
			end
			if DFGReady then dfgDmg = (DFGSlot and getDmg("DFG",enemy,myHero) or 0)	end
            if IgniteReady then iDmg = (IgniteSlot and getDmg("IGNITE",enemy,myHero) or 0) end
            extraDmg = dfgDmg + iDmg

            	KillText[i] = 1
          	if enemy.health <= aDmg*2 then
          		KillText[i] = 2
          	elseif enemy.health <= (eDmg + wDmg) and enemy.health > wDmg and eDmg then
          		KillText[i] = 3
          	elseif enemy.health <= (turretdmg + wDmg) and enemy.health > wDmg and turretdmg then
          		KillText[i] = 4
          	elseif enemy.health <= (eDmg + wDmg + turretdmg + extraDmg) and enemy.health > wDmg and turretdmg and eDmg then
          		KillText[i] = 5
          	elseif enemy.health <= (eDmg + turretdmg + missiledmg + extraDmg) and enemy.health > missiledmg and turretdmg and eDmg then
          		KillText[i] = 6
          	elseif enemy.health <= (turretupdmg + wDmg + eDmg + extraDmg) and enemy.health > turretupdmg and wDmg and eDmg then
          		KillText[i] = 7
           	end	
        end
    end
end

function Menu()
	Menu = scriptConfig("PROHeimer - Le Dongers V."..Version, "PROHeimer")
    

    Menu:addSubMenu("["..myHero.charName.." - Combo Settings]", "combosettings")
    	Menu.combosettings:addParam("combo", "SBTW Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
    	Menu.combosettings:addParam("UseE", "Use E in Combo", SCRIPT_PARAM_ONOFF, true)
    	Menu.combosettings:addParam("UseR", "Use R in Combo", SCRIPT_PARAM_ONOFF, true)
		Menu.combosettings:addParam("UseIgnite", "Use Ignite in Combo", SCRIPT_PARAM_ONOFF, true)
		Menu.combosettings:addParam("OrbWalk", "Use Orbwalker on Combo", SCRIPT_PARAM_ONOFF, true)

    Menu:addSubMenu("["..myHero.charName.." - Draw Settings", "drawsettings")
        Menu.drawsettings:addParam("drawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
        Menu.drawsettings:addParam("drawW", "Draw W Range", SCRIPT_PARAM_ONOFF, true)
        Menu.drawsettings:addParam("drawE", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
        --Menu.drawsettings:addParam("Dragon", "Draw Turrets Placement to Dragon", SCRIPT_PARAM_ONOFF, true)
        Menu.drawsettings:addParam("texts", "Draw Texts", SCRIPT_PARAM_ONOFF, true)
		Menu.drawsettings:addParam("circles", "Draw Circles around Target", SCRIPT_PARAM_ONOFF, true)
		Menu.drawsettings:addParam("FreeLagCircles","Enable lag free circles(RELOAD)", SCRIPT_PARAM_ONOFF, false)

    Menu:addSubMenu("["..myHero.charName.." - Other Settings]", "othersettings")
    	Menu.othersettings:addParam("W", "Cast W", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
        Menu.othersettings:addParam("E", "Cast E", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
        --Menu.othersettings:addParam("magnet", "Magnet Towers", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
        Menu.othersettings:addParam("KSE", "KS with E", SCRIPT_PARAM_ONOFF, true)
        Menu.othersettings:addParam("KSW", "KS with W", SCRIPT_PARAM_ONOFF, true)
        Menu.othersettings:addParam("KSIgnite", "KS With Ignite", SCRIPT_PARAM_ONOFF, false)
		Menu.othersettings:addParam("StopChannel", "AutoStun Channeling Ults (BETA)", SCRIPT_PARAM_ONOFF, false)

    Menu.combosettings:permaShow("combo")
    Menu.othersettings:permaShow("W")
    Menu.othersettings:permaShow("E")
    --Menu.othersettings:permaShow("magnet")
    Menu:addTS(ts)
end

function Checks()
	--Spell Checks
	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)

	-- --Spell CDs
	--QCurrentCD = myHero:GetSpellData(_Q).currentCd
	-- WCurrentCD = myHero:GetSpellData(_E).currentCd
	-- ECurrentCD = myHero:GetSpellData(_E).currentCd
	-- RCurrentCD = myHero:GetSpellData(_R).currentCd

	QMana = myHero:GetSpellData(_Q).mana
	WMana = myHero:GetSpellData(_W).mana
	EMana = myHero:GetSpellData(_E).mana
	RMana = myHero:GetSpellData(_R).mana

	--Summoner Checks
    IgniteReady = (IgniteSlot ~= nil and myHero:CanUseSpell(IgniteSlot) == READY)
	
	--ItemSlot Checks
    DFGSlot = GetInventorySlotItem(3128)

    --Item Checks
    DFGReady = (DFGSlot~= nil and myHero:CanUseSpell(DFGSlot) == READY)

    --Other Checks
	ts:update()

	if Menu.drawsettings.FreeLagCircles then
		_G.DrawCircle = DrawCircle2
	end

	DamageCalcs()

	if ValidTarget(ts.target) then
		WSpellPos = ProdW:GetPrediction(ts.target)
		ESpellPos = ProdE:GetPrediction(ts.target)
	end
end

function Variables()
	--Map helpers
	gameState = GetGame()
	if gameState.map.shortName == "twistedTreeline" then
		TTMAP = true
	else
		TTMAP = false
	end

	--Skill Variables
	RangeQ, RangeW, RangeE, RangeRE = 350, 1350, 925, 1800
	SpeedW, SpeedE = 905, 1000
	DelayW, DelayE = 0.3, 0.2
	WidthW, WidthE = 70, 70
	QReady, WReady, EReady, RReady = false, false, false, false
	QMana, WMana, EMana, RMana = 0,0,0,0

	Prodict = ProdictManager.GetInstance()
	ProdW = Prodict:AddProdictionObject(_W, RangeW, SpeedW, DelayW, WidthW)
	ProdWCol = Collision(RangeW, SpeedW, DelayW, WidthW)
	ProdE = Prodict:AddProdictionObject(_E, RangeE, SpeedE, DelayE, WidthE)
	
	WSpellPos, ESpellPos = nil, nil

	--Target Selector
	ts = TargetSelector(TARGET_LESS_CAST, RangeRE+150, DAMAGE_MAGIC)
	ts.name = "Heimerdinger"

	--Auxiliar Variables
 	UnitSlowed = false
 	HeimerRReady = false
	IgniteSlot = nil
	enemyHeroes = GetEnemyHeroes()

	--Damages
	DFGSlot = nil

	--Tables
	items =
	{
		BRK = {id=3153, range = 500, reqTarget = true, slot = nil },
		BFT = {id=3188, range = 750, reqTarget = true, slot = nil },
		BWC = {id=3144, range = 400, reqTarget = true, slot = nil },
		DFG = {id=3128, range = 750, reqTarget = true, slot = nil },
		HGB = {id=3146, range = 400, reqTarget = true, slot = nil },
		RSH = {id=3074, range = 350, reqTarget = false, slot = nil},
		STD = {id=3131, range = 350, reqTarget = false, slot = nil},
		TMT = {id=3077, range = 350, reqTarget = false, slot = nil},
        YGB = {id=3142, range = 350, reqTarget = false, slot = nil}
	}
	
	-- TurretSpots =
	-- {
	-- 	--Bottom towers
	-- 	{x= 10117.65 ,	y= -61.54,	z= 4804.69}, --Top One
	-- 	{x= 10330.71 ,	y= -62.09,	z= 4567.51}, --Mid One
	-- 	{x= 10295 ,		y= -61.17, 	z= 4330.39}, --Bottom One
		
	-- 	--Top Towers
	-- 	{x= 9595, 		y= -59.20, 	z= 5102.39},
	-- 	{x= 9532, 		y= -58.07, 	z= 5125.13},
	-- 	{x= 9272, 		y= -58.45, 	z= 5011.17},
	-- }

	priorityTable = {
    AP = {
        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
        "Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
        "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
    		},
    Support = {
        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
    			},
    Tank = {
        "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Nautilus", "Shen", "Singed", "Skarner", "Volibear",
        "Warwick", "Yorick", "Zac",
    		},
    AD_Carry = {
        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "Jinx", "KogMaw", "Lucian", "MasterYi", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
        "Talon","Tryndamere", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed", 
    			},
    Bruiser = {
        "Aatrox", "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nocturne", "Olaf", "Poppy",
	    "Renekton", "Rengar", "Riven", "Rumble", "Shyvana", "Trundle", "Udyr", "Vi", "MonkeyKing", "XinZhao", "Yasuo",
				},
			}

	--Priority Table Use
	if heroManager.iCount < 10 then -- borrowed from Sidas Auto Carry, modified to 3v3
        PrintChat(" >> Too few champions to arrange priority")
	elseif heroManager.iCount == 6 and TTMAP then
		ArrangeTTPrioritys()
    else
        ArrangePrioritys()
    end

	--Other Variables
	Version = "1.01"
	--Debug = false

	

	--Orbwalking Variables
    NextTick = 0
	LastAnimation = nil
	lastAttack = 0
	lastAttackCD = 0
	lastWindUpTime = 0

	--Interrupting
	Spells = {
			"AbsoluteZero", "AlZaharNetherGrasp", "CaitlynAceintheHole", "Crowstorm" ,"DrainChannel", 
			"FallenOne", "GalioIdolOfDurand", "InfiniteDuress","KatarinaR","MissFortuneBulletTime",
			"Teleport","Pantheon_GrandSkyfall_Jump", "ShenStandUnited",	"UrgotSwap2",
				}

	--Minions, Allies and Enemies
	TextList = {"Harass", "Almost dead!","W+E Kill", "Q+W Kill", "Q+W+E Kill", "E+Q+RW Kill", "E+W+RQ Kill"}
	KillText = {}
	waittxt = {} -- prevents UI lags, all credits to Dekaron
	for i=1, heroManager.iCount do waittxt[i] = i*3 end
end

function KillDraws()
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

------------------------------------------------------
--					Other Functions					--
------------------------------------------------------

function CheckIgnite()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then IgniteSlot = SUMMONER_1
        elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then IgniteSlot = SUMMONER_2
    end
end

function GetHitBoxRadius(Target)
	return GetDistance(Target, Target.minBBox)
end

function DistanceToHit(Target)
	Distance = GetDistance(Target) - GetHitBoxRadius(Target)/2
	return Distance
end

function UseItems(target)
    if target == nil then return end
    for _,item in pairs(items) do
        item.slot = GetInventorySlotItem(item.id)
        if item.slot ~= nil then
            if item.reqTarget and GetDistance(target) < item.range then
                CastSpell(item.slot, target)
                elseif not item.reqTarget then
                if (GetDistance(target) - GetHitBoxRadius(myHero) - GetHitBoxRadius(target)) < 50 then
                    CastSpell(item.slot)
                end
            end
        end
    end
end

-- Lag free circles (by barasia, vadash and viseversa)
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
    radius = radius or 300
  quality = math.max(8,round(180/math.deg((math.asin((chordlength/(2*radius)))))))
  quality = 2 * math.pi / quality
  radius = radius*.92
    local points = {}
    for theta = 0, 2 * math.pi + quality, quality do
        local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
        points[#points + 1] = D3DXVECTOR2(c.x, c.y)
    end
    DrawLines2(points, width or 1, color or 4294967295)
end

function round(num) 
	if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
end

function DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        DrawCircleNextLvl(x, y, z, radius, 1, color, 75) 
    end
end

function ArrangePrioritys()
    for i, enemy in pairs(enemyHeroes) do
        SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 2)
        SetPriority(priorityTable.Support, enemy, 3)
        SetPriority(priorityTable.Bruiser, enemy, 4)
        SetPriority(priorityTable.Tank, enemy, 5)
    end
end

function ArrangeTTPrioritys()
	for i, enemy in pairs(enemyHeroes) do
		SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 1)
        SetPriority(priorityTable.Support, enemy, 2)
        SetPriority(priorityTable.Bruiser, enemy, 2)
        SetPriority(priorityTable.Tank, enemy, 3)
	end
end

function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
        end
    end
end

------------------------------------------------------
--					Orbwalk Functions				--
------------------------------------------------------
--Based on Manciuzz Orbwalker http://pastebin.com/jufCeE0e

function OrbWalking(Unit)
	if ValidTarget(Unit) and TimeToAttack() and GetDistance(Unit) <= TrueRange(myHero) then
		myHero:Attack(Unit)
	elseif HeroCanMove() then
		MoveToCursor()
	end
end

function TrueRange(Unit)
	return Unit.range + GetDistance(Unit.minBBox)
end

function TimeToAttack()
	return (GetTickCount() + GetLatency()*0.5 > lastAttack + lastAttackCD)
end

function HeroCanMove()
	return (GetTickCount() + GetLatency()*0.5 > lastAttack + lastWindUpTime + 20)
end

function MoveToCursor()
	if GetDistance(mousePos) > 1 or LastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		Packet('S_MOVE', {x = moveToPos.x, y = moveToPos.z}):send()
	end	
end

------------------------------------------------------
--					Extra CallBacks					--
------------------------------------------------------

function OnProcessSpell(Object,Spell)
	if Object == myHero then
		if Spell.name:lower():find("attack") then
			lastAttack = GetTickCount() - GetLatency()/2
			lastWindUpTime = Spell.windUpTime*1000
			lastAttackCD = Spell.animationTime*1000
		end
	end
	--Change Menu option
	if GetDistance(Object) < RangeE and EReady and Menu.othersettings.StopChannel then
		for i=1, #Spells, 1 do
			if Spell.name == Spells[i] then
				CastE(Object) 
			end
		end
	end
end

function OnAnimation(unit,animationName)
    if unit.isMe and LastAnimation ~= animationName then LastAnimation = animationName end
end

function OnGainBuff(unit, buff)
	if unit == myHero and buff.name == "HeimerdingerR" then
		HeimerRReady = true
		RangeE = RangeRE
		WidthE = 140
		ProdE = Prodict:AddProdictionObject(_E, RangeE, SpeedE, DelayE, WidthE)
	end
	if buff.name == "heimerdingerespell" then
		UnitSlowed = true
	end
end

function OnLoseBuff(unit, buff)
	if unit == myHero and buff.name == "HeimerdingerR" then
		HeimerRReady = false
		RangeE = 925
		WidthE = 70
		ProdE = Prodict:AddProdictionObject(_E, RangeE, SpeedE, DelayE, WidthE)
	end
	if buff.name == "heimerdingerespell" then
		UnitSlowed = false
	end
end

--[[Buff INFO:
"Taunt" Turret attack
"HeimerdingerR" Pop-Up Ult
"heimerdingerespell" Heimer E Slow (+Stun?)
]]