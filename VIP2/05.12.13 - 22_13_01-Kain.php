<?php exit() ?>--by Kain 97.90.203.108
--PRO Lee Sin by BotHappy, Entryway and Skeem
--Thanks to our Challenger Tester xxlarsyxx

--[[Changelog:
	ALPHA
		1.0 -> First release
		1.1 -> Fixed some typos, code ordered
		1.2 -> Fixed double warding on WardJump functions (WardJump/Insec)
		1.3 -> Changes of Priority Table
		1.4 -> Improved casting with packets (Thanks Skeem)
		1.5 -> Insec improved.
		1.6 -> Fixed WardJump/Insec, added insec priority to selected enemy. If not, based on priority table.
		1.7 -> Changed LeeKKK to PROLeeSin. Still improving insec (WIP)
		1.8 -> Improved Removetable or wards. Also updated OnLoseBuff.
		1.9 -> Added Updater, have to modify those shit when released.
		1.10 -> Optimiced some calcs, changed a little priorities and fixing not-working-script.
		1.11a -> Added the option of totally disable move to mouse/orbwalking.
		1.12 -> Added basic Jungle clearing, Q + Q + Smite, added MoveToMouse while not Combo/Harass...
		1.13 -> Added Priority table change
		1.13a -> Fixed deleting table instead of clearing it.
		1.14 -> Added Flash+Insec support.
		1.14a -> Added q1 and q2 support to dmgs.
		1.14b -> Insec kinda fixed, some dmg errors too. Added auxiliary functions to do wardshit.
		1.15 -> FUCKING FIXED NOT Q CASTING. Added passive.
		1.16 -> Added a helper on JumpWard, to know if you are going to cast the ward well over the wall.
		1.16 -> Changed the combo to kind of a smart Combo, it will use Spells in a smarter way now. Need tweaking
		1.17 -> Jungle Clearing should be more reliable now. Added his own Passive Stacks btw.
		1.17a -> Changed Combo, should be better.
		1.17b -> Tweaking Insec, clearing menu...
		1.18 -> New Harass Combo, and Harass name.
		1.19 -> Fixed that vector shit. Added R Checker on Insec.
	]]

if myHero.charName ~= "LeeSin" or not VIP_USER then return end

require "Prodiction"
require "Collision"
--require "MapPosition"

_G.UseUpdater = true

------------------------------------------------------
--					Basic Functions					--
------------------------------------------------------

function OnLoad() --Whatever you load when the game loads should be here.
    Variables()
	CheckIgnite()
	CheckFlash()
    Menu()
    PrintChat("<font color='#FFFFFF'> >> ProLeeSin beta v1.0 loaded << </font>")
end

function OnTick() --This function is updated everyframe.
    Checks()
	AutoIgniteKS()
    if PROLeeSin.othersettings.ksR then KSwithR() end
	if ValidTarget(ts.target) then
		if PROLeeSin.combosettings.combo then CastCombo(ts.target) end
        if PROLeeSin.peelersettings.zcombo then PeelerCombo(ts.target) end
		if PROLeeSin.harasssettings.harass then HarassFar(ts.target) end
	elseif not ValidTarget(ts.target) then
		if PROLeeSin.combosettings.combo then MoveToCursor() end
        if PROLeeSin.peelersettings.zcombo then MoveToCursor() end
		if PROLeeSin.harasssettings.harass then MoveToCursor() end
	end
	if ValidTarget(tsInsec.target) then
		if PROLeeSin.insecsettings.insec then
			if PROLeeSin.insecsettings.priority == 1 then
				if PROLeeSin.insecsettings.flash then 
					InsecFlash(tsInsec.target) 
				elseif GotWard and not FlashReady and RReady then 
					Insec(tsInsec.target) 
				end
			elseif PROLeeSin.insecsettings.priority == 0 then
				if GotWard then
					Insec(tsInsec.target)
				elseif PROLeeSin.insecsettings.flash and FlashReady then
					InsecFlash(tsInsec.target)
				end
			end
		end
	elseif not ValidTarget(tsInsec.target) then 
		if PROLeeSin.insecsettings.insec then MoveToCursor() end
	end
    if PROLeeSin.othersettings.wjump then WardJump() end
	if PROLeeSin.junglesettings.jungle then JungleClear() end
	if PROLeeSin.junglesettings.QSmiteQ and SmiteSlot ~= nil then QSmiteQ() end
	Resets()
end

function OnDraw() --Draws, updated everyframe
    if PROLeeSin.drawsettings.drawQ then
        if Q1Ready then
            DrawCircle(myHero.x, myHero.y, myHero.z, q1Range, 0x7F006E)
            if ValidTarget(ts.target) then
                if GetDistance(ts.target) - GetHitBoxRadius(ts.target)*0.5 < q1Range then
                    DrawCircle(ts.target.x, ts.target.y, ts.target.z, GetHitBoxRadius(ts.target)*0.5, 0x7F006E)
                end
            end
        end
    end
    if PROLeeSin.drawsettings.drawW then
        if W1Ready then
            DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0x5F9F9F)
        end
    end
    if PROLeeSin.drawsettings.drawE then
        if E1Ready then
            DrawCircle(myHero.x, myHero.y, myHero.z, e1Range, 0xCC3233)
            if ValidTarget(ts.target) then
                if GetDistance(ts.target) - GetHitBoxRadius(ts.target)*0.5 < e1Range then
                    DrawCircle(ts.target.x, ts.target.y, ts.target.z, GetHitBoxRadius(ts.target)*0.5, 0xCC3233)
                end
            end
        end
    end
    if PROLeeSin.drawsettings.drawR then
        if RReady then
            DrawCircle(myHero.x, myHero.y, myHero.z, rRange, 0x458B00)
            if ValidTarget(ts.target) then
                if GetDistance(ts.target) - GetHitBoxRadius(ts.target)*0.5 < rRange then
                    DrawCircle(ts.target.x, ts.target.y, ts.target.z, GetHitBoxRadius(ts.target)*0.5, 0x458B00)
                end
            end
        end
    end
    if PROLeeSin.drawsettings.texts then KillDraws() end
	--Another draws (Optional ones)
	if PROLeeSin.drawsettings.WardHelper and PROLeeSin.othersettings.wjump then
		if mousePos.x ~= mp.x or mousePos.z ~= mp.z then
			mp.x, mp.z, p = mousePos.x, mousePos.z, FindNearestNonWall(mousePos.x, mousePos.y, mousePos.z, wardRange, 20)
		end
		if p then
			DrawCircle3D(p.x, p.y, p.z, 50, 2, ARGB(255, 255, 255, 255), 20)
		end
		--DrawCircle3D(mousePos.x, mousePos.y, mousePos.z, 25, 2, ARGB(150, 255, 0, 0), 10)
	end
	if not RReady and PROLeeSin.insecsettings.insec then 
		PrintFloatText(myHero, 0, "R on Cooldown")
	end
end

------------------------------------------------------
--					Combo Functions					--
------------------------------------------------------

function CastCombo(Target) --Not working well, just written to have an idea
	local qComboDmg = getDmg("Q", Target, myHero, 1)
	local q2ComboDmg = getDmg("Q", Target, myHero, 2)
	local eComboDmg = getDmg("E", Target, myHero)
	local rComboDmg = getDmg("R", Target, myHero) 
	local AAComboDmg = getDmg("AD", Target, myHero)
	local igniteComboDmg = 0

	if IgniteReady then igniteComboDmg = getDmg("IGNITE", Target, myHero) end

	OrbWalking(Target)
	UseItems(Target)

	if GetDistance(Target) <= e1Range and E1Ready and not CurrentPassive then
		CastSpellAttack(E1Ready, e1Range, _E, Target)
	elseif Q1Ready and ValidTarget(Target, q1Range) and not CurrentPassive then CastQ(Target) 
	end

	if Q2Ready and QLanded and RReady and ValidTarget(Target, rRange) and PROLeeSin.combosettings.useR and Target.health <= q2ComboDmg + rComboDmg + igniteComboDmg then 
		CastOnObject(IgniteSlot, Target)
		CastOnObject(_R, Target)
		DelayAction(function()CastSpell(_Q) end, 0.6)
	end

	if Q2Ready and QLanded and ValidTarget(Target, q2Range) then
		if ValidTarget(Target, e1Range) and not CurrentPassive and E1Ready then
			CastSpellAttack(E1Ready, e1Range, _E, Target)
		elseif not CurrentPassive and ValidTarget(Target, 300) then
			CastSpellAttack(Q2Ready, q2Range, _Q, Target, QLanded)
		elseif ValidTarget(Target, q2Range) then
			CastSpellAttack(Q2Ready, q2Range, _Q, Target, QLanded)
		end
	end
	
	if E2Ready and not CurrentPassive and ELanded and ValidTarget(Target, e2Range) then
		CastSpellAttack(E2Ready, e2Range, _E, Target, ELanded)
	end 

	if PROLeeSin.combosettings.useW then
		if W1Ready then CastSpellAttack(W1Ready, 250, _W, Target)
		elseif W2Ready then	CastSpellAttack(W2Ready, 250, _W, Target)
		end
	end

	if PROLeeSin.combosettings.useR and RReady and Target.health < rComboDmg then 
		CastOnObject(_R, Target)
	end
end

function PeelerCombo(Target)
    if PROLeeSin.peelersettings.UseMoveToMouse then MoveToCursor() end
    if Q1Ready and RReady and ValidTarget(Target, q1Range) then CastQ(Target) end
    if RReady and Q2Ready and QLanded then
		if ValidTarget(Target, rRange) then
			CastOnObject(_R, Target)
			DelayAction(function()CastSpell(_Q) end, 0.6)
		elseif ValidTarget(Target, q2Range) and GetDistance(Target) > rRange then
			CastSpell(_Q)
			DelayAction(function() CastOnObject(_R, Target) end, 0.6)
		end
    end
	--ADD W USAGE HERE
end

function HarassFar(Target)
	if PROLeeSin.harasssettings.UseMoveToMouse then MoveToCursor() end
	if myHero.mana >= 120 and W1Ready and Q1Ready and ValidTarget(Target, q1Range) then CastQ(Target) end
	if Q2Ready and ValidTarget(Target, q2Range) and QLanded then 
		CastSpell(_Q) 
		QHarass= true
	end
	if PROLeeSin.harasssettings.useE and E1Ready and myHero.mana >=100 then
		if QHarass and GetDistance(myHero, Target) < 100 then 
			CastSpell(_E)
			EHarass = true
		end
		if EHarass and W1Ready then DelayAction(AutoWMinion, 0.5) end
	elseif not EHarass and W1Ready and QHarass and GetDistance(myHero, Target) < 75 then AutoWMinion()
	end
end

function HarassNear(Target)
	if PROLeeSin.harasssettings.UseMoveToMouse then OrbWalking(Target) end
	if GetDistance(Target,e1Range) and E1Ready and not CurrentPassive then
		CastSpellAttack(E1Ready, e1Range, _E, Target)
	elseif E2Ready and GetDistance(Target,e2Range) then
		CastSpell(_E)
	elseif GetDistance(Target,q1Range) and Q1Ready and not CurrentPassive then
		CastQ(Target)
	elseif GetDistance(Target,q2Range) and Q2Ready and QLanded and not CurrentPassive then
		CastSpell(_Q)
		QHarass= true
	end
	if QHarass and W1Ready then DelayAction(AutoWMinion, 0.5) end
end

function Insec(Target)
--Add flash support
	if not RReady then return end
	local hero = nil
    if Q1Ready and PROLeeSin.insecsettings.UseMoveToMouse then MoveToCursor() end
    if Q1Ready and myHero.mana > 125 and W1Ready and RReady and GotWard and ValidTarget(Target, q1Range) then 
		CastQ(Target)
	end
    if Q2Ready and ValidTarget(Target, q2Range) and QInsecLanded then
		MyPos = Vector(myHero.x, myHero.y, myHero.z)
        CastSpell(_Q)
    end
	if MyPos ~= nil then
		if CountAllyHeroInRange(1500, myHero) > 0 and HeroWithMoreAlliesNear(1500) ~= nil then
			hero = HeroWithMoreAlliesNear(1500)
			CastWardInsec = myHero + (Vector(Target.x, Target.y, Target.z) - Vector(hero.x,hero.y,hero.z)):normalized()*200
		elseif (CountAllyHeroInRange(1500, myHero) == 0 or HeroWithMoreAlliesNear(1500) == nil) then
			CastWardInsec = myHero + (Vector(Target.x, Target.y, Target.z) - MyPos):normalized()*200
		end
	end
	
	if W1Ready and RReady and GotWard and not (Q1Ready and Q2Ready) and GetDistance(myHero, Target) < 150 then
		if RSStoneReady and not WardInsecReady then
			CastSpell(RSStoneSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
			return
		elseif SStoneReady and not WardInsecReady then
			CastSpell(SStoneSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
			return 
		elseif WrigglesReady and not WardInsecReady then
			CastSpell(WrigglesSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
			return 
		elseif SWardReady and not WardInsecReady then
			CastSpell(SWardSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
			return
		elseif VWardReady and not WardInsecReady then
			CastSpell(VWardSlot, CastWardInsec.x, CastWardInsec.z)
			WardInsecReady = true
			return
		end
	end
	if W1Ready and RReady and WardInsecReady then
		for _, Ward in ipairs(InsecWardTable) do
			if GetDistance(Ward) < wRange then
				CastOnObject(_W, Ward)
				JumpdToWard = true
			end
		end
	end
	if RReady and JumpdToWard then-- and ValidTarget(Target, rRange) then
		DelayAction(function() CastOnObject(_R, Target) end, math.abs(0.2-(GetLatency()*0.001)))
	end
end

function InsecFlash(Target)
	if not RReady then return end
	local hero = nil
	if Q1Ready and PROLeeSin.insecsettings.UseMoveToMouse then MoveToCursor() end
	if ValidTarget(Target, q1Range) and Q1Ready and RReady and FlashReady then
		CastQ(Target)
	end
	if Q2Ready and ValidTarget(Target, q2Range) and QInsecLanded then
		MyPos = Vector(myHero.x, myHero.y, myHero.z)
        CastSpell(_Q)
    end
	if MyPos ~= nil then
		if CountAllyHeroInRange(1500, myHero) > 0 and HeroWithMoreAlliesNear(1500) ~= nil then
			hero = HeroWithMoreAlliesNear(1500)
			CastFlash = myHero + (Vector(Target.x, Target.y, Target.z) - Vector(hero.x,hero.y,hero.z)):normalized()*250
		elseif (CountAllyHeroInRange(1500, myHero) == 0 or HeroWithMoreAlliesNear(1500) == nil) then
			CastFlash = myHero + (Vector(Target.x, Target.y, Target.z) - MyPos):normalized()*250
		end
	end
	if RReady and GetDistance(Target) < 250 then
		RCasted = true
		CastOnObject(_R, Target)
	end
end

function CastSpellAttack(SpellReady, SpellRange, Spell, Target, Optional, TargetSpell)
    if Optional == nil then Optional = true end

    if SpellReady and ValidTarget(Target, SpellRange) and Optional then
        if TimeToAttack() and GetDistance(Target) < 250 then
			if TargetSpell ~= nil and Spell == _Q then
				DelayAction(function() CastQ(TargetSpell) end, 0.3)
			else
				DelayAction(function() CastSpell(Spell) end, 0.3)
			end
            return true
        else
            if TargetSpell ~= nil and Spell == _Q then
				CastQ(TargetSpell)
			else
				CastSpell(Spell)
			end
            return true
        end
    end
    return false
end

function AutoWMinion() --Thanks to Skeem
	MinionTable = DeepCopyTable(allyMinions.objects)
	table.sort(MinionTable, function(x,y) return GetDistance(x) > GetDistance(y) end)
	for i, wMinion in ipairs(MinionTable) do
		if GetDistance(myHero, wMinion) < wRange then
			CastOnObject(_W, wMinion)
		end
	end
end

------------------------------------------------------
--					Orbwalk Functions				--
------------------------------------------------------
--Based on Manciuzz Orbwalker http://pastebin.com/jufCeE0e

function OrbWalking(Unit)
	if ValidTarget(Unit) and TimeToAttack() and GetDistance(Unit) <= myHero.range + GetDistance(myHero.minBBox) then
		myHero:Attack(Unit)
	elseif HeroCanMove() then
		MoveToCursor()
	end
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
--					Auxiliary Functions				--
------------------------------------------------------

function Variables()
	--mapPosition = MapPosition()
	gameState = GetGame()
	if gameState.map.shortName == "twistedTreeline" then
		TTMAP = true
	else
		TTMAP = false
	end
	--Ranges
    q1Range, q2Range, wRange, e1Range, e2Range, rRange = 1000, 1300, 700, 425, 600, 375
    --QData
    qSpeed, qDelay, qWidth = 1800, 0.5, 60
	
	--Damages
	TrinitySlot, SheenSlot, BWCSlot, BotrkSlot, YoumuSlot, HydraSlot, EntropySlot = nil, nil, nil, nil, nil, nil, nil
	q1Dmg, q2Dmg, eDmg, AADmg, IgniteDmg, rDmg = 0,0,0,0,0,0
	SheenDmg, BWCDmg, TrinityDmg, BotrkDmg, HydraDmg, TiamatDmg, EntropyDmg = 0,0,0,0,0,0,0
	ComboFast, Combo1, Combo2 = 0,0,0
    --Prodiction
    Prodict = ProdictManager.GetInstance()
    ProdictQ = Prodict:AddProdictionObject(_Q, q1Range, 1800, 0.250, 60)
	ProdictQCollision = Collision(q1Range, 1800, 0.250, 60)
    --Priority Table of TS -> Based on Manciuszz http://pastebin.com/0mzbDAvv
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
        "Renekton", "Rengar", "Riven", "Rumble", "Shyvana", "Trundle", "Udyr", "Vi", "MonkeyKing", "XinZhao",
    },
}
	--TS
    ts = TargetSelector(TARGET_NEAR_MOUSE, q2Range, DAMAGE_PHYSICAL)
	tsInsec = TargetSelector(TARGET_PRIORITY, q2Range, DAMAGE_PHYSICAL, true )
    ts.name = "LeeSin"
	
	--Minions, Allies and Enemies
    enemyHeroes = GetEnemyHeroes()
	allyHeroes = GetAllyHeroes()
	allyMinions = minionManager(MINION_ALLY, wRange, player, MINION_SORT_HEALTH_ASC)
	
	--Priority Table Use
	if heroManager.iCount < 10 then -- borrowed from Sidas Auto Carry
        PrintChat(" >> Too few champions to arrange priority")
	elseif heroManager.iCount == 6 and TTMAP then
		ArrangeTTPrioritys()
    else
        ArrangePrioritys()
    end
	--Orbwalking Variables
    NextTick = 0
	LastAnimation = nil
	lastAttack = 0
	lastAttackCD = 0
	lastWindUpTime = 0
    
	--Items
    items = -- With entropy
    {
        BRK = {id=3153, range = 500, reqTarget = true, slot = nil },
        ETP = {id=3184, range = 350, reqTarget = true, slot = nil },
        BWC = {id=3144, range = 400, reqTarget = true, slot = nil },
        DFG = {id=3128, range = 750, reqTarget = true, slot = nil },
        HGB = {id=3146, range = 400, reqTarget = true, slot = nil },
        RSH = {id=3074, range = 350, reqTarget = false, slot = nil},
        STD = {id=3131, range = 350, reqTarget = false, slot = nil},
        TMT = {id=3077, range = 350, reqTarget = false, slot = nil},
        YGB = {id=3142, range = 350, reqTarget = false, slot = nil}
    }
	
	--WardJump Variables
	WardTable = {}
	InsecWardTable = {}
	WardReady = false
	--local wards = {3154,2044,2043,2049,2045,3340,3350,3361,3362} --Wriggles, Normal Greens, Vision, RSStone, Sightstone, Warding Totem, Greater Totem, Greater Stealth Totem, Greater Vision Totem
	SWard, VWard, SStone, RSStone, Wriggles = 2044, 2043, 2049, 2045, 3154
	SWardSlot, VWardSlot, SStoneSlot, RSStoneSlot, WrigglesSlot = nil, nil, nil, nil, nil
	RSStoneReady, SStoneReady, SWardReady, VWardReady, WrigglesReady = false, false, false, false, false
	wardRange = 600
	
	--Function Aux Helpers
	QLanded = false
	ESlow = false
	Passive = false
	PassiveStacks = 0
	CurrentPassive = false
	
	QHarass = false
	EHarass = false
	WardInsecReady = false
	JumpdToWard = false
	QInsecLanded = false
	
	--Update Variables
	
	versionGOE = 0.113 -- current version ALPHA = 0.1 -> BETA = 0.2 -> Final 1.0
	SCRIPT_NAME_GOE = "PROLeeSin"
	
	needUpdate_GOE = false
	needRun_GOE = true
	--URL_GOE = --"http://dlr5668.cuccfree.org/"..SCRIPT_NAME_GOE..".lua"
	--PATH_GOE = --BOL_PATH.."Scripts\\"..SCRIPT_NAME_GOE..".lua"
	
	--Jungle Variables
	JungleMobs = {}
	JungleFocusMobs = {}
	JungleCurrentPassive = false
	SmiteRange = 600
	SmiteSlot = nil
	SmiteDmg = 0
	SmiteReady = false
	
	q1DmgMob, q2DmgMob = 0,0
	
	if TTMAP then --Probably need work
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
			-- Stolen from Apple who Stole it from Sida --
	JungleMobNames = { -- List stolen from SAC Revamped. Sorry, Sida!
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
	DragonRDY, VilemawRDY, NashorRDY = false, false, false
	
	enemyMinions = minionManager(MINION_ENEMY, SmiteRange, player, MINION_SORT_HEALTH_ASC)
	CheckSmite()

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
	
	FlashSlot = nil
	IgniteSlot = nil
	CastFlash = Vector(0,0,0)
	
	mp, p = D3DXVECTOR3(0, 0, 0), nil
end

function Checks()
	--Spell Checks
    Q1Ready = ((myHero:CanUseSpell(_Q) == READY) and myHero:GetSpellData(_Q).name == "BlindMonkQOne")
	Q2Ready = ((myHero:CanUseSpell(_Q) == READY) and myHero:GetSpellData(_Q).name == "blindmonkqtwo")
	W1Ready = ((myHero:CanUseSpell(_W) == READY) and myHero:GetSpellData(_W).name == "BlindMonkWOne")
	W2Ready = ((myHero:CanUseSpell(_W) == READY) and myHero:GetSpellData(_W).name == "blindmonkwtwo")
    E1Ready = ((myHero:CanUseSpell(_E) == READY) and myHero:GetSpellData(_E).name == "BlindMonkEOne")
	E2Ready = ((myHero:CanUseSpell(_E) == READY) and myHero:GetSpellData(_E).name == "blindmonketwo")
    RReady = (myHero:CanUseSpell(_R) == READY)
	--Summoner Checks
    IgniteReady = (IgniteSlot ~= nil and myHero:CanUseSpell(IgniteSlot) == READY)
	SmiteReady = (SmiteSlot ~= nil and myHero:CanUseSpell(SmiteSlot) == READY)
	FlashReady = (FlashSlot ~= nil and myHero:CanUseSpell(FlashSlot) == READY)
    
	--ItemSlot Checks
    TrinitySlot = GetInventorySlotItem(3078)
    SheenSlot = GetInventorySlotItem(3057)
    BCWSlot = GetInventorySlotItem(3144)
    BotrkSlot = GetInventorySlotItem(3153)
    YoumuSlot = GetInventorySlotItem(3142)
    TiamatSlot = GetInventorySlotItem(3077)
    HydraSlot = GetInventorySlotItem(3074)
    EntropySlot = GetInventorySlotItem(3184)
	--Ward Check
	SWardSlot = GetInventorySlotItem(SWard)
	VWardSlot = GetInventorySlotItem(VWard)
	SStoneSlot = GetInventorySlotItem(SStone) 
	RSStoneSlot = GetInventorySlotItem(RSStone)
	WrigglesSlot = GetInventorySlotItem(Wriggles)
	--Ward Checks
	RSStoneReady = (RSStoneSlot ~= nil and CanUseSpell(RSStoneSlot) == READY)
	SStoneReady = (SStoneSlot ~= nil and CanUseSpell(SStoneSlot) == READY)
	SWardReady = (SWardSlot ~= nil and CanUseSpell(SWardSlot) == READY)
	VWardReady = (VWardSlot ~= nil and CanUseSpell(VWardSlot) == READY)
	WrigglesReady = (WrigglesSlot ~= nil and CanUseSpell(WrigglesSlot) == READY)
	--Got a ward to place to jump
	GotWard = WrigglesReady or RSStoneReady or SStoneReady or SWardReady or VWardReady
    --Item Checks
    TrinityReady = (TrinitySlot ~= nil and myHero:CanUseSpell(TrinitySlot) == READY)
    SheenReady = (SheenSlot ~= nil and myHero:CanUseSpell(SheenSlot) == READY)
    BCWReady = (BCWSlot~= nil and myHero:CanUseSpell(BCWSlot) == READY)
    BotrkReady = (BotrkSlot ~= nil and myHero:CanUseSpell(BotrkSlot) == READY)
    YoumuReady = (YoumuSlot ~= nil and myHero:CanUseSpell(YoumuSlot) == READY)
    TiamatReady = (TiamatSlot ~= nil and myHero:CanUseSpell(TiamatSlot) == READY)
    HydraReady = (HydraSlot ~= nil and myHero:CanUseSpell(HydraSlot) == READY)
    EntropyReady = (EntropySlot ~= nil and myHero:CanUseSpell(EntropySlot) == READY)
    --Other Checks
    GetDamages()
	JungleDmgs()
    ts:update()
	tsInsec:update()
	allyMinions:update()
	enemyMinions:update()
end

function Resets()
	if Passive then
		if PassiveStacks > (2-PROLeeSin.combosettings.passivestacks) then
			CurrentPassive = true
		else
			CurrentPassive = false
		end
	elseif not Passive then
			CurrentPassive = false
	end
	if Passive then
		if PassiveStacks > (2-PROLeeSin.junglesettings.passivestacks) then
			JungleCurrentPassive = true
		else
			JungleCurrentPassive = false
		end
	elseif not Passive then
			JungleCurrentPassive = false
	end
	--To reset insec
	if not PROLeeSin.insecsettings.insec then
		JumpdToWard = false
		WardInsecReady = false
			--InsecWardTable = {}
		for k,v in pairs(InsecWardTable) do InsecWardTable[k]=nil end
		RCasted = false
		DrawWard = false
	end
	
	--Harass Settings
	if PROLeeSin.harasssettings.harass then 
		if Q1Ready then QHarass = false end
		if E1Ready then EHarass = false end
	end
	if not PROLeeSin.othersettings.wjump then
		for k,v in pairs(WardTable) do WardTable[k]=nil end
		WardReady = false
	end
end

function CheckIgnite()
    if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then IgniteSlot = SUMMONER_1
        elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then IgniteSlot = SUMMONER_2
    end
end

function CheckFlash()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerFlash") then FlashSlot = SUMMONER_1
		elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerFlash") then FlashSlot = SUMMONER_2
	end
end

function GetHitBoxRadius(Unit)
	return GetDistance(Unit, Unit.minBBox)
end

function DeepCopyTable(orig) --To copy a whole table
    local orig_type = type(orig)
    local copy
    if orig_type == 'table' then
        copy = {}
        for orig_key, orig_value in next, orig, nil do
            copy[DeepCopyTable(orig_key)] = DeepCopyTable(orig_value)
        end
        setmetatable(copy, DeepCopyTable(getmetatable(orig)))
    else -- number, string, boolean, etc
        copy = orig
    end
    return copy
end

function q2ComboDmg(Damage, Target)
    if Damage == nil then Damage = 0 end
    local PredHealth = Target.health - Damage
    local q2Dmg = ((myHero:GetSpellData(_Q).level*30) + 20) + (myHero.addDamage*0.9) + (0.08*(Target.maxHealth-PredHealth))
    return myHero:CalcDamage(Target, q2Dmg)
end

function CastQ(Unit)
    if GetDistance(Unit) - GetHitBoxRadius(Unit)*0.5 < q1Range and ValidTarget(Unit) then
        QPos = ProdictQ:GetPrediction(Unit)
        local willCollide = ProdictQCollision:GetMinionCollision(QPos, myHero)
        if not willCollide then CastSpell(_Q, QPos.x, QPos.z) end
    end
end

function HeroWithMoreAlliesNear(range) --It gets the hero with more heroes around and returns that object
	local currentnumber = 0
	local allyInRange = nil
    for i = 1, heroManager.iCount, 1 do
        local hero = heroManager:getHero(i)
        if hero ~=nil and GetDistance(hero) <range and hero.team == myHero.team and hero.networkID ~= myHero.networkID then
            if CountAllyHeroInRange(range, hero) > currentnumber then
				currentnumber = CountAllyHeroInRange(range, hero)
				allyInRange = hero
				
			else
				allyInRange = nil
				currentnumber = 0
			end
		end
    end
	return allyInRange
end

function CountAllyHeroInRange(range, hero) --Count ally heroes near that hero of your team, not including you
    local allyInRange = 0
    for i = 1, heroManager.iCount, 1 do
        local ally = heroManager:getHero(i)
        if ally ~=nil and GetDistance(ally) <range and ally.team == myHero.team and ally.networkID ~= myHero.networkID then
            allyInRange = allyInRange + 1
        end
    end
    return allyInRange
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

function GetDamages()
    for i = 1, heroManager.iCount do
        local EnemyDraws = heroManager:getHero(i)
		if ValidTarget(EnemyDraws) then
			q1Dmg = getDmg("Q", EnemyDraws, myHero, 1)
			q2Dmg = getDmg("Q", EnemyDraws, myHero, 2)
			eDmg = getDmg("E", EnemyDraws, myHero)
			rDmg = getDmg("R", EnemyDraws, myHero)
			AADmg = getDmg("AD", EnemyDraws, myHero)
			IgniteDmg = getDmg("IGNITE", EnemyDraws, myHero)
			SheenDmg = getDmg("SHEEN", EnemyDraws, myHero)
			BWCDmg = getDmg("BWC", EnemyDraws, myHero)
			TrinityDmg = getDmg("TRINITY", EnemyDraws, myHero)
			BotrkDmg = getDmg("RUINEDKING", EnemyDraws, myHero)
			if EntropySlot ~= nil then EntropyDmg = 80 end
			if BotrkReady then
				AADmg = AADmg + 0.05*EnemyDraws.health
			end
		end
	end
    ItemsDmg = SheenDmg + BWCDmg + TrinityDmg + BotrkDmg + HydraDmg + TiamatDmg + EntropyDmg
    --Combos goes here
    
    ComboFast = q1Dmg + rDmg + q2Dmg
    Combo1 = q1Dmg + q2Dmg + eDmg + rDmg + IgniteDmg + ItemsDmg
    Combo2 = q1Dmg + q2Dmg + eDmg + AADmg*2 + IgniteDmg + ItemsDmg
end

function CastOnObject(Spell, Object)
	Packet("S_CAST", {spellId = Spell, targetNetworkId = Object.networkID}):send()
end

function CastCoord(Spell, X, Z)
	Packet("S_CAST", {spellId = Spell, x = X, y = Z}):send()
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

------------------------------------------------------
--					Jungle Stuff					--
------------------------------------------------------

function CheckSmite()
	if myHero:GetSpellData(SUMMONER_1).name:find("Smite") then SmiteSlot = SUMMONER_1
		elseif myHero:GetSpellData(SUMMONER_2).name:find("Smite") then SmiteSlot = SUMMONER_2 end
end

function JungleDmgs()
	--Jungle Calculations
	if SmiteSlot ~= nil then SmiteDmg = math.max(20*myHero.level+370,30*myHero.level+330,40*myHero.level+240,50*myHero.level+100) end
	
	local JungleMob = GetJungleMob()
	for _, Mob in ipairs(JungleFocusMobs) do
		if ValidTarget(Mob, SmiteRange) then
			q1DmgMob = getDmg("Q", Mob, myHero, 1)
			q2DmgMob = getDmg("Q", Mob, myHero, 2)
		end
	end
end

function JungleClear()
	local JungleMob = GetJungleMob()
	if PROLeeSin.junglesettings.Orbwalk then
		if ValidTarget(JungleMob) then
			OrbWalking(JungleMob)
		else
			MoveToCursor()
		end
	end
	if ValidTarget(JungleMob) and not JungleCurrentPassive then
		UseItems(JungleMob)
		if PROLeeSin.junglesettings.jungleE and GetDistance(JungleMob) <= e1Range and E1Ready then 
			CastSpell(_E, JungleMob.x, JungleMob.z) 
		elseif PROLeeSin.junglesettings.jungleW and GetDistance(JungleMob) <= 250 and W1Ready then 
			CastSpell(_W)
		elseif PROLeeSin.junglesettings.jungleW and W2Ready then
			CastSpell(_W)
		elseif PROLeeSin.junglesettings.jungleQ and GetDistance(JungleMob) <= q1Range and Q1Ready then 
			CastSpell(_Q, JungleMob.x, JungleMob.z) 
		elseif PROLeeSin.junglesettings.jungleQ and GetDistance(JungleMob) <= q2Range and Q2Ready then
			CastSpell(_Q)
		elseif PROLeeSin.junglesettings.jungleE and GetDistance(JungleMob) <= e2Range and E2Ready then
			CastSpell(_E)
		end
	end
end

function GetJungleMob()
	for _, Mob in pairs(JungleFocusMobs) do
		if ValidTarget(Mob, SmiteRange) then return Mob end
	end
	for _, Mob in pairs(JungleMobs) do
		if ValidTarget(Mob, SmiteRange) then return Mob end
	end
end

function QSmiteQ()
	local JungleMob = GetJungleMob()
	for _, Mob in pairs(JungleFocusMobs) do
		if Mob.name:find("Dragon") or Mob.name:find("Worm") or Mob.name:find("LizardElder") or Mob.name:find("AncientGolem") or Mob.name:find("Spider") then
			if ValidTarget(Mob, q1Range) and Mob.health < (SmiteDmg + q1DmgMob + q2DmgMob) and SmiteReady and Q1Ready and myHero.mana >= 80 then
				if Q1Ready then 
					CastQ(Mob) 
					DelayAction(function() CastSpell(_Q) end, 0.3)
				end			
				if GetDistance(Mob) < 100 and SmiteReady then
					CastOnObject(SmiteSlot, Mob)
				end
			end
		end
	end
end

------------------------------------------------------
--					Other Functions					--
------------------------------------------------------

function UseItems(Target)
    if not ValidTarget(Target) then return end
    for _,item in pairs(items) do
        item.slot = GetInventorySlotItem(item.id)
        if item.slot ~= nil then
            if item.reqTarget and GetDistance(Target) < item.range then
                CastOnObject(item.slot, Target)
                elseif not item.reqTarget then
                if (GetDistance(Target) - GetHitBoxRadius(myHero) - GetHitBoxRadius(Target)) < 50 then
                    CastSpell(item.slot)
                end
            end
        end
    end
end

function KillDraws()
    for i = 1, heroManager.iCount do
        local EnemyDraws = heroManager:getHero(i)
        if ValidTarget(EnemyDraws) then
            if EnemyDraws.health < (rDmg) then
                PrintFloatText(EnemyDraws, 0, "Kill with R!")
            elseif EnemyDraws.health < IgniteDmg then
                PrintFloatText(EnemyDraws, 0, "Ignite!")
            elseif EnemyDraws.health < ComboFast then
                PrintFloatText(EnemyDraws, 0, "Q-Q-R")
            elseif EnemyDraws.health < Combo1 then
                PrintFloatText(EnemyDraws, 0, "Q-Q-E-R +Ign+Itm")
            elseif EnemyDraws.health < Combo2 then
                PrintFloatText(EnemyDraws, 0, "Q-Q-E +Ign+Itm")
            elseif EnemyDraws.health > Combo2 then
                PrintFloatText(EnemyDraws, 0, "Harass")
            end
        end
    end
end

function AutoIgniteKS()
    if PROLeeSin.othersettings.igniteKS and IgniteReady then
        local IgniteDMG = 50 + (20 * myHero.level)
        for _, enemy in pairs(GetEnemyHeroes()) do
            if ValidTarget(enemy, 600) and enemy.health <= IgniteDMG then
                CastOnObject(IgniteSlot, enemy)
            end
        end
    end
end

function WardJump()
	MoveToCursor()
	local Coordenates = mousePos
		--if not mapPosition:inWall(Point(Coordenates.x, Coordenates.z)) or mapPosition:intersectsWall(Point(Coordenates.x, Coordenates.z)) then
	if W1Ready and GetDistance(Coordenates) <= wardRange and GotWard and not WardReady then
		if RSStoneReady then
			CastCoord(RSStoneSlot, Coordenates.x, Coordenates.z)
			WardReady = true
			return
		elseif SStoneReady and not WardReady then
			CastCoord(SStoneSlot, Coordenates.x, Coordenates.z)
			WardReady = true
			return 
		elseif WrigglesReady and not WardReady then
			CastCoord(WrigglesSlot, Coordenates.x, Coordenates.z)
			WardReady = true
			return 
		elseif SWardReady and not WardReady then
			CastCoord(SWardSlot, Coordenates.x, Coordenates.z)
			WardReady = true
			return
		elseif VWardReady and not WardReady then
			CastCoord(VWardSlot, Coordenates.x, Coordenates.z)
			WardReady = true
			return
		end
	end
	if W1Ready and WardReady then
		for _, Ward in ipairs(WardTable) do
			if GetDistance(Ward) < wRange then
				CastOnObject(_W, Ward)
			end
		end
	end
end

function KSwithR()
    for i = 1, heroManager.iCount do
        local Enemy = heroManager:getHero(i)
        if RReady and ValidTarget(Enemy, rRange, true) and Enemy.health < getDmg("R",Enemy,myHero) + 30 then
            CastOnObject(_R, Enemy)
        end
    end
end

function Menu()
    PROLeeSin = scriptConfig("PROLeeSin - The Playmaker", "PROLeeSin beta v1.0")
    --Settings
    HKCombo = 32
    HKInsec = string.byte("C") --67
    HKHarass = string.byte("T") --84
    HKWardJump = string.byte ("Z") --90
    HKZoningCombo = string.byte ("G") --71
	HKJungle = string.byte("X")
    
    PROLeeSin:addSubMenu("["..myHero.charName.." - Combo Settings]", "combosettings")
    	PROLeeSin.combosettings:addParam("combo", "Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, HKCombo)
    	PROLeeSin.combosettings:addParam("useW", "Use W in Combo", SCRIPT_PARAM_ONOFF, true)
    	PROLeeSin.combosettings:addParam("useR", "Use R in Combo", SCRIPT_PARAM_ONOFF, false)
		--PROLeeSin.combosettings:addParam("useIgnite", "Use Ignite in Combo", SCRIPT_PARAM_ONOFF, true) -- will come in another update
		PROLeeSin.combosettings:addParam("usepassive", "Use Passive at Combo", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.combosettings:addParam("passivestacks", "No. Passive Stacks While Combo", SCRIPT_PARAM_SLICE, 1, 0, 2, 0)
		PROLeeSin.combosettings:addParam("UseOrbWalk", "Use Orbwalker on Combo", SCRIPT_PARAM_ONOFF, true)

    PROLeeSin:addSubMenu("["..myHero.charName.." - Harass Settings]", "harasssettings")
        PROLeeSin.harasssettings:addParam("harass", "Harass Key", SCRIPT_PARAM_ONKEYDOWN, false, HKHarass)
        PROLeeSin.harasssettings:addParam("useE", "Use E in Harass", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.harasssettings:addParam("UseMoveToMouse", "Move to mouse on Harass", SCRIPT_PARAM_ONOFF, true)
		--PROLeeSin.harasssettings:addParam("useAA", "Use AA in Harass", SCRIPT_PARAM_ONOFF, false) -- next update

    PROLeeSin:addSubMenu("["..myHero.charName.." - Insec Settings]", "insecsettings")
        PROLeeSin.insecsettings:addParam("insec", "Insec Key", SCRIPT_PARAM_ONKEYDOWN, false, HKInsec)
		PROLeeSin.insecsettings:addParam("UseMoveToMouse", "Move to mouse on Insec", SCRIPT_PARAM_ONOFF, true)
		--PROLeeSin.insecsettings:addParam("ManualInsec", "Use Manual Insec", SCRIPT_PARAM_ONOFF, false) -- Just an idea, maybe in another update
        PROLeeSin.insecsettings:addParam("flash", "Use Flash", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.insecsettings:addParam("priority", "Priority: 0 = Ward, 1 = Flash", SCRIPT_PARAM_SLICE, 0, 0, 1, 0)

    PROLeeSin:addSubMenu("["..myHero.charName.." - Carry Peeler Settings]", "peelersettings")
        PROLeeSin.peelersettings:addParam("zcombo", "Peel Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, HKZoningCombo)
		PROLeeSin.peelersettings:addParam("UseMoveToMouse", "Move to mouse on Peeler Combo", SCRIPT_PARAM_ONOFF, true)
		--PROLeeSin.peelersettings:addParam("useQ", "Use Q to get near the enemy", SCRIPT_PARAM_ONOFF, true) -- ??
		--PROLeeSin.peelersettings:addParam("useW", "Use W to the near ally", SCRIPT_PARAM_ONOFF, false) -- ??
        --Don't know what else yet huehue
	PROLeeSin:addSubMenu("["..myHero.charName.." - Jungle Clearing Settings]", "junglesettings")
		PROLeeSin.junglesettings:addParam("jungle", "Jungle Clearing", SCRIPT_PARAM_ONKEYDOWN, false, HKJungle)
		PROLeeSin.junglesettings:addParam("Orbwalk", "Orbwalk on Jungle Clearing", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("jungleQ", "Use Q at Jungle Clearing", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("jungleW", "Use W at Jungle Clearing", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("jungleE", "Use E at Jungle Clearing", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.junglesettings:addParam("QSmiteQ", "Q+Smite+Q Big Objectives", SCRIPT_PARAM_ONOFF, true)
		--PROLeeSin.junglesettings:addParam("WWSmite", "Auto W+W+Smite when low life", SCRIPT_PARAM_ONOFF, true) -- ??
		PROLeeSin.junglesettings:addParam("passivestacks", "No. Passive Stacks While Jungling", SCRIPT_PARAM_SLICE, 2, 0, 2, 0)
	
     PROLeeSin:addSubMenu("["..myHero.charName.." - Draw Settings", "drawsettings")
        PROLeeSin.drawsettings:addParam("drawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("drawW", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("drawE", "Draw W Range", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("drawR", "Draw R Range", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("WardHelper", "Draw Ward Helper", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.drawsettings:addParam("texts", "Draw Texts", SCRIPT_PARAM_ONOFF, true)

    PROLeeSin:addSubMenu("["..myHero.charName.." - Other Settings]", "othersettings")
        PROLeeSin.othersettings:addParam("ksR", "KS with R", SCRIPT_PARAM_ONOFF, true)
        PROLeeSin.othersettings:addParam("igniteKS", "Auto Ignite KS", SCRIPT_PARAM_ONOFF, true)
		PROLeeSin.othersettings:addParam("wjump", "Ward Jump", SCRIPT_PARAM_ONKEYDOWN, false, HKWardJump)

    PROLeeSin.combosettings:permaShow("combo")
    PROLeeSin.harasssettings:permaShow("harass")
    PROLeeSin.insecsettings:permaShow("insec")
    PROLeeSin.peelersettings:permaShow("zcombo")
    PROLeeSin.othersettings:permaShow("wjump")
	PROLeeSin.junglesettings:permaShow("jungle")
    PROLeeSin:addTS(ts)
end

------------------------------------------------------
--					Extra CallBacks					--
------------------------------------------------------

function OnProcessSpell(Object,Spell)
	if Object == myHero then
		if Spell.name:lower():find("attack") then
			lastAttack = GetTickCount() - GetLatency()*0.5
			lastWindUpTime = Spell.windUpTime*1000
			lastAttackCD = Spell.animationTime*1000
		end
		if RCasted and Spell.name == "BlindMonkRKick" then 
			CastSpell(FlashSlot, CastFlash.x, CastFlash.z)
		end
	end
end

function OnAnimation(Unit,AnimationName)
	if Unit.isMe and LastAnimation ~= AnimationName then LastAnimation = AnimationName end
end

function OnCreateObj(Object)
	if Object and Object.valid and (string.find(Object.name, "Ward") ~= nil or string.find(Object.name, "Wriggle") ~= nil) then 
		if PROLeeSin.insecsettings.insec then table.insert(InsecWardTable, Object)
		else table.insert(WardTable, Object) 
		end
	end
	if FocusJungleNames[Object.name] then
			table.insert(JungleFocusMobs, Object)
		elseif JungleMobNames[Object.name] then
            table.insert(JungleMobs, Object)
	end
end

function OnDeleteObj(Object)
	for i, Mob in pairs(JungleMobs) do
		if Object.name == Mob.name then
			table.remove(JungleMobs, i)
		end
	end
	for i, Mob in pairs(JungleFocusMobs) do
		if Object.name == Mob.name then
			table.remove(JungleFocusMobs, i)
		end
	end
end

function OnGainBuff(Unit, buff)
	if Unit==ts.target and buff.name == "BlindMonkQOne" or "blindmonkqonechaos" then
		QLanded = true
	end
	if Unit==ts.target and buff.name == "BlindMonkEOne" then
		ELanded = true
	end
	if Unit==ts.target and buff.name == "BlindMonkETwoMissile" then
		ESlow = true
	end
	if Unit == myHero and buff.name == "blindmonkpassive_cosmetic" then
		Passive = true
		PassiveStacks = 2
	end
	if Unit==tsInsec.target and buff.name == "BlindMonkQOne" or "blindmonkqonechaos" then
		QInsecLanded = true
	end
end

function OnLoseBuff(Unit, buff)
	if Unit~=myHero and buff.name == "BlindMonkQOne" or "blindmonkqonechaos" then
		QLanded = false
	end
	if Unit==ts.target and buff.name == "BlindMonkEOne" then
		ELanded = false
	end
	if Unit == myHero and buff.name == "blindmonkpassive_cosmetic" then
		Passive = false
		PassiveStacks = 0
	end
	if Unit==tsInsec.target and buff.name == "BlindMonkQOne" or "blindmonkqonechaos" then
		QInsecLanded = false
	end
end

function OnUpdateBuff(Unit, buff)
	if Unit == myHero and buff.name == "blindmonkpassive_cosmetic" then
		PassiveStacks = buff.stack
	end
end

------------------------------------------------------
--					Update Stuff					-- --by Vadash
------------------------------------------------------
collectgarbage()
function CheckVersionGOE(data)
	local onlineVerGOE = tonumber(data)
	if type(onlineVerGOE) ~= "number" then return end
	if onlineVerGOE and onlineVerGOE > versionGOE then
		print("<font color='#000000'>"..SCRIPT_NAME_GOE..": </font>".."<font color='#FF0000'> There is a new version. Auto Update. Don't F9 till done...</font>") 
		needUpdate_GOE = true
	else
		print("<font color='#000000'>"..SCRIPT_NAME_GOE..": </font>".." <font color='#00FF00'>"..versionGOE.." loaded</font>")   
	end
end

function UpdateScriptGOE()
	if needRun_GOE then
		needRun_GOE = false
		if _G.UseUpdater == nil or _G.UseUpdater == true then GetAsyncWebResult("dlr5668.cuccfree.org", SCRIPT_NAME_GOE.."Ver.lua", CheckVersionGOE) end
	end

	if needUpdate_GOE then
		needUpdate_GOE = false
		DownloadFile(URL_GOE, PATH_GOE, function()
			if FileExist(PATH_GOE) then
				print("<font color='#000000'>"..SCRIPT_NAME_GOE..": </font>".."<font color='#FF0000'> Updated, Reload script for new version.</font>")
			end
		end)
	end
end

AddTickCallback(UpdateScriptGOE)

---------------------------------- END UPDATE -----------------------------------------------------------------

--[[Buffnames: 
	Q on Enemy: BlindMonkQOne, blindmonkqonechaos
	E1 on Enemy: BlindMonkEOne
	E2 on Enemy: BlindMonkETwoMissile
	Passive: blindmonkpassive_cosmetic
]]

--[[BuffLocations = {
		{x = 3662, y = 54, z = 7598}, -- Blue Team Blue Buff
		{x = 7422, y = 57, z = 3904}, -- Blue Team Red Buff 
		{x = 6501, y = 54, z = 10575}, -- Purple Team Red Buff
		{x = 10359, y = 54, z = 6849} -- Purple Team Blue Buff

]]