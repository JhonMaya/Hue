<?php exit() ?>--by 149kg 81.166.153.19
--Lee Singa KKK Script by BotHappy and Entryway

--[[TODO
Combo functions

*Insec
*Jump to a ward around option while chasing at combo
*Jump to a ward on Harrass
*Jungle tricks?

ADD Trinket support -> check ITEM_7 GetItem(ITEM_7)
HOW COMBO STUFF SHOULD WORK
	if not possible to burst then
	1 - Cast an ability -> Q1 or E1
	2 - AA until passive is of
	3 - Cast another ability
	4 ??????
	end
Exceptions
	if distancetoenemy++ then Cast E2times
	if distancetoenemy-- or life-- then Cast W2times
	if neardead then CastR then wardsave or smth
]]

if myHero.charName ~= "LeeSin" or not VIP_USER then return end

require "Prodiction"
require "Collision"

function OnLoad() --Whatever you load when the game loads should be here.
    Variables()
    CheckIgnite()
    Menu()
    PrintChat("Lee Singa KKK first test here!")
end

function OnTick() --This function is updated everyframe.
    Checks()
	AutoIgniteKS()
    if LeeKKK.othersettings.ksR then KSwithR() end
	if ValidTarget(ts.target) then
		if LeeKKK.combosettings.combo then CastCombo(ts.target) end
        if LeeKKK.peelersettings.zcombo then CastZoningCombo(ts.target) end
		if LeeKKK.harrasssettings.harrass then Harrass(ts.target) end
		if LeeKKK.insecsettings.insec and myHero.mana > 125 then Insec(ts.target) end
	end
    if LeeKKK.wjump then WardJump() end
	if Passive then
		if PassiveStacks > (2-LeeKKK.combosettings.passivestacks) then
			CurrentPassive = true
		else
			CurrentPassive = false
		end
	elseif not Passive then
			CurrentPassive = false
	end
end

function OnDraw() --Draws, updated everyframe
    if LeeKKK.drawsettings.drawQ then
        if Q1Ready then
            DrawCircle(myHero.x, myHero.y, myHero.z, q1Range, 0x7F006E)
            if ValidTarget(ts.target) then
                if GetDistance(ts.target) - getHitBoxRadius(ts.target)/2 < q1Range then
                    DrawCircle(ts.target.x, ts.target.y, ts.target.z, getHitBoxRadius(ts.target)/2, 0x7F006E)
                end
            end
        end
    end
    if LeeKKK.drawsettings.drawW then
        if W1Ready then
            DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0x5F9F9F)
        end
    end
    if LeeKKK.drawsettings.drawE then
        if E1Ready then
            DrawCircle(myHero.x, myHero.y, myHero.z, e1Range, 0xCC3233)
            if ValidTarget(ts.target) then
                if GetDistance(ts.target) - getHitBoxRadius(ts.target)/2 < e1Range then
                    DrawCircle(ts.target.x, ts.target.y, ts.target.z, getHitBoxRadius(ts.target)/2, 0xCC3233)
                end
            end
        end
    end
    if LeeKKK.drawsettings.drawR then
        if RReady then
            DrawCircle(myHero.x, myHero.y, myHero.z, rRange, 0x458B00)
            if ValidTarget(ts.target) then
                if GetDistance(ts.target) - getHitBoxRadius(ts.target)/2 < rRange then
                    DrawCircle(ts.target.x, ts.target.y, ts.target.z, getHitBoxRadius(ts.target)/2, 0x458B00)
                end
            end
        end
    end
end
    --if LeeKKK.texts then KillDraws() end
--Another draws (Optional ones)

function CheckIgnite()
    if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then IgniteSlot = SUMMONER_1
        elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then IgniteSlot = SUMMONER_2
    end
end

function getHitBoxRadius(Unit)
	return GetDistance(Unit, Unit.minBBox)
end

function Variables()
    q1Range = 1000
    q2Range = 1300
    wRange = 700
    e1Range = 425
    e2Range = 600
    rRange = 375
    
    qSpeed = 1800
    qDelay = 0.5
    qWidth = 60
	
	TrinitySlot, SheenSlot, BWCSlot, BotrkSlot, YoumuSlot, HydraSlot, EntropySlot = nil, nil, nil, nil, nil, nil, nil
	qDmg, eDmg, AADmg, IgniteDmg, rDmg = 0,0,0,0,0
	SheenDmg, BWCDmg, TrinityDmg, BotrkDmg, HydraDmg, TiamatDmg, EntropyDmg = 0,0,0,0,0,0,0
	ComboFast, Combo1, Combo2 = 0,0,0
    
    Prodict = ProdictManager.GetInstance()
    ProdictQ = Prodict:AddProdictionObject(_Q, q1Range, 1800, 0.250, 60)
	ProdictQCollision = Collision(q1Range, 1800, 0.250, 60)
    
    priorityTable = {
    AP = {
        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
        "Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
        "Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra", "MasterYi",
    },
    Support = {
        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
    },
 
    Tank = {
        "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",
        "Warwick", "Yorick", "Zac",
    },
 
    AD_Carry = {
        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "Jinx", "KogMaw", "Lucian", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
        "Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed",
    },
 
    Bruiser = {
        "Aatrox", "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",
        "Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao",
    },
}

    ts = TargetSelector(TARGET_NEAR_MOUSE, q1Range+200, DAMAGE_PHYSICAL)
    ts.name = "LeeSin"
	
    NextTick = 0
	IgniteSlot = nil

	lastAnimation = nil
	lastAttack = 0
	lastAttackCD = 0
	lastWindUpTime = 0
    
    enemyHeroes = GetEnemyHeroes()
	allyHeroes = GetAllyHeroes()
	allyMinions = minionManager(MINION_ALLY, wRange, player, MINION_SORT_HEALTH_ASC)
    
    if heroManager.iCount < 10 then -- borrowed from Sidas Auto Carry
        PrintChat(" >> Too few champions to arrange priority")
    else
        arrangePrioritys()
    end
    
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
	
	WardTable = {}
	--local wards = {3154,2044,2050,2043,2049,2045,3340,3350,3361,3362} --Wriggles, Normal Greens, Explorer, Vision, RSStone, Sightstone, Warding Totem, Greater Totem, Greater Stealth Totem, Greater Vision Totem
	SWard, VWard, SStone, RSStone, Wriggles = 2044, 2043, 2049, 2045, 3154
	SWardSlot, VWardSlot, SStoneSlot, RSStoneSlot, WrigglesSlot = nil, nil, nil, nil, nil
	RSStoneReady, SStoneReady, SWardReady, VWardReady, WrigglesReady = false, false, false, false, false
	wardRange = 600
	
	QLanded = false
	ESlow = false
	Passive = false
	PassiveStacks = 0
	CurrentPassive = false
	
	QHarrass = false
	EHarrass = false
end

function arrangePrioritys()
    for i, enemy in ipairs(enemyHeroes) do
        SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 2)
        SetPriority(priorityTable.Support, enemy, 3)
        SetPriority(priorityTable.Bruiser, enemy, 4)
        SetPriority(priorityTable.Tank, enemy, 5)
    end
end

function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
        end
    end
end

function Menu()
    LeeKKK = scriptConfig("Lee Sin - The Blind Monk", "LeeSingaKKK")
    --Settings
    HKCombo = 32
    HKInsec = 67
    HKHarrass = 84
    HKWardJump = 90
    HKZoningCombo = 71
    
    LeeKKK:addSubMenu("["..myHero.charName.." - Combo Settings]", "combosettings")
    	LeeKKK.combosettings:addParam("combo", "Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, HKCombo)
    	LeeKKK.combosettings:addParam("useW", "Use W in Combo", SCRIPT_PARAM_ONOFF, false)
    	LeeKKK.combosettings:addParam("useR", "Use R in Combo", SCRIPT_PARAM_ONOFF, false)
		LeeKKK.combosettings:addParam("usepassive", "Use Passive at Combo", SCRIPT_PARAM_ONOFF, true)
		LeeKKK.combosettings:addParam("passivestacks", "No. Passive Stacks While Combo", SCRIPT_PARAM_SLICE, 1, 0, 2, 0)

    LeeKKK:addSubMenu("["..myHero.charName.." - Harras Settings]", "harrasssettings")
        LeeKKK.harrasssettings:addParam("harrass", "Harrass Key", SCRIPT_PARAM_ONKEYDOWN, false, HKHarrass)
        LeeKKK.harrasssettings:addParam("useE", "Use E in Harrass", SCRIPT_PARAM_ONOFF, false)
		--LeeKKK.harrasssettings:addParam("useAA", "Use AA in Harrass", SCRIPT_PARAM_ONOFF, false)

    LeeKKK:addSubMenu("["..myHero.charName.." - Insec Settings]", "insecsettings")
        LeeKKK.insecsettings:addParam("insec", "Insec Key", SCRIPT_PARAM_ONKEYDOWN, false, HKInsec)
        LeeKKK.insecsettings:addParam("useFlash", "Use Flash", SCRIPT_PARAM_ONOFF, false)

    LeeKKK:addSubMenu("["..myHero.charName.." - Carry Peeler Settings]", "peelersettings")
        LeeKKK.peelersettings:addParam("zcombo", "Peel Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, HKZoningCombo)
        --Don't know what else yet huehue

     LeeKKK:addSubMenu("["..myHero.charName.." - Draw Settings", "drawsettings")
        LeeKKK.drawsettings:addParam("drawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
        LeeKKK.drawsettings:addParam("drawW", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
        LeeKKK.drawsettings:addParam("drawE", "Draw W Range", SCRIPT_PARAM_ONOFF, true)
        LeeKKK.drawsettings:addParam("drawR", "Draw R Range", SCRIPT_PARAM_ONOFF, true)

    LeeKKK:addSubMenu("["..myHero.charName.." - Other Settings]", "othersettings")
        LeeKKK.othersettings:addParam("ksR", "KS with R", SCRIPT_PARAM_ONOFF, true)
        LeeKKK.othersettings:addParam("igniteKS", "Auto Ignite KS", SCRIPT_PARAM_ONOFF, true)
        LeeKKK.othersettings:addParam("drawCol", "Draw Collision", SCRIPT_PARAM_ONOFF, true)
        LeeKKK.othersettings:addParam("texts", "Draw Texts", SCRIPT_PARAM_ONOFF, true)

    LeeKKK:addParam("wjump", "Ward Jump", SCRIPT_PARAM_ONKEYDOWN, false, HKWardJump)

    LeeKKK.combosettings:permaShow("combo")
    LeeKKK.harrasssettings:permaShow("harrass")
    LeeKKK.insecsettings:permaShow("insec")
    LeeKKK.peelersettings:permaShow("zcombo")
    LeeKKK:permaShow("wjump")
    LeeKKK:addTS(ts)
end

function Checks()
    Q1Ready = ((myHero:CanUseSpell(_Q) == READY) and myHero:GetSpellData(_Q).name == "BlindMonkQOne")
	Q2Ready = ((myHero:CanUseSpell(_Q) == READY) and myHero:GetSpellData(_Q).name == "blindmonkqtwo")
	W1Ready = ((myHero:CanUseSpell(_W) == READY) and myHero:GetSpellData(_W).name == "BlindMonkWOne")
	W2Ready = ((myHero:CanUseSpell(_W) == READY) and myHero:GetSpellData(_W).name == "blindmonkwtwo")
    E1Ready = ((myHero:CanUseSpell(_E) == READY) and myHero:GetSpellData(_E).name == "BlindMonkEOne")
	E2Ready = ((myHero:CanUseSpell(_E) == READY) and myHero:GetSpellData(_E).name == "blindmonketwo")
    RReady = (myHero:CanUseSpell(_R) == READY)
    
	if Q1Ready then QHarrass = false end
	if E1Ready then EHarrass = false end
	
    IgniteReady = (IgniteSlot ~= nil and myHero:CanUseSpell(IgniteSlot) == READY)
    
    TrinitySlot = GetInventorySlotItem(3078)
    SheenSlot = GetInventorySlotItem(3057)
    BCWSlot = GetInventorySlotItem(3144)
    BotrkSlot = GetInventorySlotItem(3153)
    YoumuSlot = GetInventorySlotItem(3142)
    TiamatSlot = GetInventorySlotItem(3077)
    HydraSlot = GetInventorySlotItem(3074)
    EntropySlot = GetInventorySlotItem(3184)
	
	SWardSlot = GetInventorySlotItem(SWard)
	VWardSlot = GetInventorySlotItem(VWard)
	SStoneSlot = GetInventorySlotItem(SStone) 
	RSStoneSlot = GetInventorySlotItem(RSStone)
	WrigglesSlot = GetInventorySlotItem(Wriggles)
	
	if RSStoneSlot ~= nil and CanUseSpell(RSStoneSlot) == READY then
		RSStoneReady = true
	else RRStoneReady = false
	end
	if SStoneSlot ~= nil and CanUseSpell(SStoneSlot) == READY then
		SStoneReady = true
	else SStoneReady = false
	end
	if SWardSlot ~= nil then
		SWardReady = true 
	else SWardReady = false
	end
	if VWardSlot ~= nil then
		VWardReady = true 
	else VWardReady = false
	end
	if WrigglesSlot ~= nil and CanUseSpell(WrigglesSlot) then
		WrigglesReady = true 
	else WrigglesReady = false
	end
	
	GotWard = WrigglesReady or RSStoneReady or SStoneReady or SWardReady or VWardReady
    
    TrinityReady = (TrinitySlot ~= nil and myHero:CanUseSpell(TrinitySlot) == READY)
    SheenReady = (SheenSlot ~= nil and myHero:CanUseSpell(SheenSlot) == READY)
    BCWReady = (BCWSlot~= nil and myHero:CanUseSpell(BCWSlot) == READY)
    BotrkReady = (BotrkSlot ~= nil and myHero:CanUseSpell(BotrkSlot) == READY)
    YoumuReady = (YoumuSlot ~= nil and myHero:CanUseSpell(YoumuSlot) == READY)
    TiamatReady = (TiamatSlot ~= nil and myHero:CanUseSpell(TiamatSlot) == READY)
    HydraReady = (HydraSlot ~= nil and myHero:CanUseSpell(HydraSlot) == READY)
    EntropyReady = (EntropySlot ~= nil and myHero:CanUseSpell(EntropySlot) == READY)
    
    GetDamages()
    ts:update()
	allyMinions:update()
end

function AlliesAroundHeroes(range)
	local currentnumber = 0
	local allyInRange = nil
    for i = 1, heroManager.iCount, 1 do
        local hero = heroManager:getHero(i)
        if hero ~=nil and GetDistance(hero) <range and hero.team == myHero.team and hero.networkID ~= myHero.networkID then
            if CountAllyHeroInRange(range, hero) > currentnumber then
				currentnumber = CountAllyHeroInRange(range, hero)
				allyInRange = hero
				return allyInRange
			else
				allyInRange = nil
				currentnumber = 0
			end
		end
    end
end

function CountAllyHeroInRange(range, hero)
    local allyInRange = 0
    for i = 1, heroManager.iCount, 1 do
        local ally = heroManager:getHero(i)
        if ally ~=nil and GetDistance(ally) <range and ally.team == myHero.team and ally.networkID ~= myHero.networkID then
            allyInRange = allyInRange + 1
        end
    end
    return allyInRange
end

function GetDamages()
    for i = 1, heroManager.iCount do
        local EnemyDraws = heroManager:getHero(i)
		if ValidTarget(EnemyDraws) then
			qDmg = getDmg("Q", EnemyDraws, myHero)
			eDmg = getDmg("E", EnemyDraws, myHero)
			rDmg = getDmg("R", EnemyDraws, myHero)
			AADmg = getDmg("AD", EnemyDraws, myHero)
			IgniteDmg = getDmg("IGNITE", EnemyDraws, myHero)
			SheenDmg = getDmg("SHEEN", EnemyDraws, myHero)
			BWCDmg = getDmg("BWC", EnemyDraws, myHero)
			TrinityDmg = getDmg("TRINITY", EnemyDraws, myHero)
			BotrkDmg = getDmg("RUINEDKING", EnemyDraws, myHero)
			if HydraSlot ~= nil then HydraDmg = AADmg*0.6 end
			if TiamatSlot ~= nil then TiamatDmg = AADmg*0.6 end
			if EntropySlot ~= nil then EntropyDmg = 80 end
			if BotrkReady then
				AADmg = AADmg + 0.05*EnemyDraws.health
			end
		end
	end
    ItemsDmg = SheenDmg + BWCDmg + TrinityDmg + BotrkDmg + HydraDmg + TiamatDmg + EntropyDmg
    --Combos goes here
    
    ComboFast = qDmg*2 + rDmg
    Combo1 = qDmg*2 + eDmg + rDmg + IgniteDmg + ItemsDmg
    Combo2 = qDmg*2 + eDmg + AADmg*2 + IgniteDmg + ItemsDmg
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
                PrintFloatText(EnemyDraws, 0, "Harrass")
            end
        end
    end
end

function KSwithR()
    for i = 1, heroManager.iCount do
        local Enemy = heroManager:getHero(i)
        if RReady and ValidTarget(Enemy, rRange, true) and Enemy.health < getDmg("R",Enemy,myHero) + 30 then
            CastSpell(_R, Enemy)
        end
    end
end

function CastQ(Unit)
    if GetDistance(Unit) - getHitBoxRadius(Unit)/2 < q1Range and ValidTarget(Unit) then
        QPos = ProdictQ:GetPrediction(Unit)
        local willCollide = ProdictQCollision:GetMinionCollision(QPos, myHero)
        if not willCollide then CastSpell(_Q, QPos.x, QPos.z) end
    end
end

function WardJump()
	local Coordenates = mousePos
	if W1Ready and GetDistance(Coordenates) <= wardRange and GotWard then
		if RSStoneReady then
			CastSpell(RSStoneSlot, Coordenates.x, Coordenates.z)
		elseif SStoneReady then
			CastSpell(SStoneSlot, Coordenates.x, Coordenates.z)
		elseif WrigglesReady then
			CastSpell(WrigglesSlot, Coordenates.x, Coordenates.z)
		elseif SWardReady then
			CastSpell(SWardSlot, Coordenates.x, Coordenates.z)
		elseif VWardReady then
			CastSpell(VWardSlot, Coordenates.x, Coordenates.z)
		end
	end
	if W1Ready then
		for _, Ward in ipairs(WardTable) do
			if GetDistance(Ward) < wRange then
				CastSpell(_W, Ward)
			end
		end
	end
	if not W1Ready then table.remove(WardTable) end
end

function UseItems(target)
    if not ValidTarget(target) then return end
    for _,item in pairs(items) do
        item.slot = GetInventorySlotItem(item.id)
        if item.slot ~= nil then
            if item.reqTarget and GetDistance(target) < item.range then
                CastSpell(item.slot, target)
                elseif not item.reqTarget then
                if (GetDistance(target) - getHitBoxRadius(myHero) - getHitBoxRadius(target)) < 50 then
                    CastSpell(item.slot)
                end
            end
        end
    end
end

function AutoIgniteKS()
    if LeeKKK.othersettings.igniteKS and IgniteReady then
        local IgniteDMG = 50 + (20 * myHero.level)
        for _, enemy in pairs(GetEnemyHeroes()) do
            if ValidTarget(enemy, 600) and enemy.health <= IgniteDMG then
                CastSpell(IgniteSlot, enemy)
            end
        end
    end
end

function CastCombo(Target) --Not working well, just written to have an idea
	OrbWalking(Target)
	UseItems(Target)
	if not CurrentPassive and Q1Ready and ValidTarget(Target, q1Range) then CastQ(Target) end	--Use as iniciator/Gapcloser at combo
	
	if GetDistance(Target) < 300 and not CurrentPassive then
		CastSpellAttack(Q1Ready, q1Range, _Q, Target, nil, TargetSpell)
		CastSpellAttack(Q2Ready, q2Range, _Q, Target, QLanded)
		CastSpellAttack(E1Ready, e1Range, _E, Target)
		if not LeeKKK.combosettings.useW then 
			CastSpellAttack(E2Ready, e2Range, _E, Target)
		elseif LeeKKK.combosettings.useW and (myHero.mana >= 80 or not W2Ready) then
			CastSpellAttack(E2Ready, e2Range, _E, Target)
		end

		if LeeKKK.combosettings.useW then
			CastSpellAttack(W1Ready, 250, _W, Target)
			CastSpellAttack(W2Ready, 250, _W, Target)
		end
	end
	if GetDistance(Target) > 300 then
		if E2Ready and ValidTarget(Target, e2Range) then
			CastSpellAttack(E2Ready, e2Range, _E, Target)
		elseif Q2Ready and ValidTarget(Target, q2Range) then 
			CastSpell(_Q) 
		end
	end
end

function CastZoningCombo(Target)
    MoveToCursor()
    if Q1Ready and RReady and ValidTarget(Target, q1Range) then CastQ(Target) end
    if RReady and Q2Ready and QLanded then
		if ValidTarget(Target, rRange) then
			CastSpell(_R, Target)
			DelayAction(function()CastSpell(_Q) end, 0.6)
		elseif ValidTarget(Target, q2Range) and GetDistance(Target) > rRange then
			CastSpell(_Q)
			DelayAction(function() CastSpell(_R, Target) end, 0.6)
		end
    end
end

function Harrass(Target)
	MoveToCursor()
	if myHero.mana >= 120 and W1Ready and Q1Ready and ValidTarget(Target, q1Range) then CastQ(Target) end
	if Q2Ready and ValidTarget(Target, q2Range) and QLanded then 
		CastSpell(_Q) 
		QHarrass= true
	end
	--if LeeKKK.harrasssettings.useAA then myHero:Attack(Target) end
	if LeeKKK.harrasssettings.useE and E1Ready and myHero.mana >=100 then
		if QHarrass and GetDistance(myHero, Target) < 150 then 
			CastSpell(_E)
			EHarrass = true
		end
		if EHarrass and W1Ready and GetDistance(myHero, Target) < 150 then DelayAction(AutoWMinion, 0.5) end
	elseif not EHarrass and W1Ready and QHarrass and GetDistance(myHero, Target) < 150 then AutoWMinion()
	end
end

function AutoWMinion() --Thanks to Skeem
	MinionTable = DeepCopyTable(allyMinions.objects)
	table.sort(MinionTable, function(x,y) return GetDistance(x) > GetDistance(y) end)
	for i, wMinion in ipairs(MinionTable) do
		if GetDistance(myHero, wMinion) < wRange then
			CastSpell(_W, wMinion)
		end
	end
end

function DeepCopyTable(orig)
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

function Insec(Target)
--Add flash support
    MoveToCursor()
    if Q1Ready and ValidTarget(Target, q1Range) then 
		CastQ(Target)
	end
    if Q2Ready and ValidTarget(Target, q2Range) and QLanded then
		MyPos = Vector(myHero.x, myHero.y, myHero.z)
        CastSpell(_Q)
    end
	if CountAllyHeroInRange(1500, myHero) > 0 and AlliesAroundHeroes(1500) ~= nil then
		if W1Ready and GotWard and not (Q1Ready and Q2Ready) and GetDistance(myHero, Target) < 150 then
			if AlliesAroundHeroes(1500) ~= nil then
				CastWard = myHero + (Vector(Target.x, Target.y, Target.z) - Vector(AlliesAroundHeroes(1500).x,AlliesAroundHeroes(1500).y,AlliesAroundHeroes(1500).z)):normalized()*rRange
			end
			if RSStoneReady then
				CastSpell(RSStoneSlot, CastWard.x, CastWard.z)
			elseif SStoneReady then
				CastSpell(SStoneSlot, CastWard.x, CastWard.z)
			elseif WrigglesReady then
				CastSpell(WrigglesSlot, CastWard.x, CastWard.z)
			elseif SWardReady then
				CastSpell(SWardSlot, CastWard.x, CastWard.z)
			elseif VWardReady then
				CastSpell(VWardSlot, CastWard.x, CastWard.z)
			end
			if W1Ready then
				for _, Ward in ipairs(WardTable) do
					if GetDistance(Ward) < wRange then
						CastSpell(_W, Ward)
					end
				end
			end
			if not W1Ready then table.remove(WardTable) end
		end
	elseif W1Ready and GotWard and not (Q1Ready and Q2Ready) and GetDistance(myHero, Target) < 150 then
		CastWard = myHero + (Vector(Target.x, Target.y, Target.z) - MyPos):normalized()*rRange
		if RSStoneReady then
			CastSpell(RSStoneSlot, CastWard.x, CastWard.z)
		elseif SStoneReady then
			CastSpell(SStoneSlot, CastWard.x, CastWard.z)
		elseif WrigglesReady then
			CastSpell(WrigglesSlot, CastWard.x, CastWard.z)
		elseif SWardReady then
			CastSpell(SWardSlot, CastWard.x, CastWard.z)
		elseif VWardReady then
			CastSpell(VWardSlot, CastWard.x, CastWard.z)
		end
		if W1Ready then
			for _, Ward in ipairs(WardTable) do
				if GetDistance(Ward) < wRange then
					CastSpell(_W, Ward)
				end
			end
		end
		if not W1Ready then table.remove(WardTable) end
	end
	if RReady and not W1Ready and ValidTarget(Target) then 
		DelayAction(function() CastSpell(_R, Target) end, 0.2) 
	end
end

function CastSpellAttack(SpellReady, SpellRange, Spell, Target, Optional, TargetSpell)
    if Optional == nil then Optional = true end

    if SpellReady and ValidTarget(Target, SpellRange) and Optional then
        if TimeToAttack() and GetDistance(Target) < 250 then
			if TargetSpell ~= nil and Spell == _Q then -- We should add a delay here or erase attack.
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
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end

function HeroCanMove()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end

function MoveToCursor()
	if GetDistance(mousePos) > 1 or lastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
	end	
end

function OnProcessSpell(object,spell)
	if object == myHero then
		if spell.name:lower():find("attack") then
			lastAttack = GetTickCount() - GetLatency()/2
			lastWindUpTime = spell.windUpTime*1000
			lastAttackCD = spell.animationTime*1000
		end
	end
end

function OnAnimation(Unit,animationName)
	if Unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function OnCreateObj(object)
	if object and object.valid and (string.find(object.name, "Ward") ~= nil or string.find(object.name, "Wriggle") ~= nil) then 
		table.insert(WardTable, object) 
	end
end

function OnGainBuff(Unit, buff)
	if Unit==ts.target and buff.name == "BlindMonkQOne" then
		QLanded = true
	end
	if Unit==ts.target and buff.name == "BlindMonkETwoMissile" then
		ESlow = true
	end
	if Unit == myHero and buff.name == "blindmonkpassive_cosmetic" then
		Passive = true
		PassiveStacks = 2
	end
end

function OnLoseBuff(unit, buff)
	if unit == myHero and buff.name == "blindmonkpassive_cosmetic" then
		Passive = false
		PassiveStacks = 0
	end
end

function OnUpdateBuff(unit, buff)
	if unit == myHero and buff.name == "blindmonkpassive_cosmetic" then
		PassiveStacks = buff.stack
	end
end
--[[Buffnames: 
	Q on Enemy: BlindMonkQOne
	E on Enemy: BlindMonkETwoMissile
]]