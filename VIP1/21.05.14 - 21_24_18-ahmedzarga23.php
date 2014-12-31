<?php exit() ?>--by ahmedzarga23 24.10.244.64
if myHero.charName ~= 'Yasuo' then return end
--[[
	Made By Detuks
	Ty Hex for helping
	Tnx Sida for Priority table/Items and Summoner spells ( ye ye im lazy ;D )

]]
--Version 0.04

require 'Prodiction'

--[[VARS]]--
local EnemyTable
local EnemyMinions
local JungleMinions
local JumpableEnemies = {}
local qRange1,qRange2 = 450,900
local eRange, rRange = 475, 1300
local dPosQ = {}
local castQforSweep = false
local dashPos = {}
local towers = {}
local qRange = 450


local dashing = false

local knockUpReady = false
--[[VARS]]--
class 'TickManager'
function TickManager:__init(ticksPerSecond)
        self.TPS = ticksPerSecond
        self.lastClock = 0
        self.currentClock = 0
end
 
function TickManager:__type()
        return "TickManager"
end
 
function TickManager:setTPS(ticksPerSecond)
        self.TPS = ticksPerSecond
end
 
function TickManager:getTPS(ticksPerSecond)
        return self.TPS
end
 
function TickManager:isReady()
        self.currentClock = os.clock()
        if self.currentClock < self.lastClock + (1 / self.TPS) then return false end
        self.lastClock = self.currentClock
        return true
end
 
local tm = TickManager(20)
 
function OnTick()

	QREADY = (myHero:CanUseSpell(_Q) == READY)
    EREADY = (myHero:CanUseSpell(_E) == READY)
    RREADY = (myHero:CanUseSpell(_R) == READY)

	SummonerOnTick()
	qRange = (knockUpReady and 900) or 450
	ts.range = qRange
    ts:update()
	es:update()
	
	--getJumpable()
	 if YasuoConfig.Basic.KillDemAll then
		if es.target and es.target.type == myHero.type then
						if GetDistance(es.target) < 230 then
							attackEnemy(es.target)
						end
                        if QREADY then CastQ(es.target) end
                        if EREADY then
                                if not inTurretRange(dashPos) and GetDistance(es.target) <= eRange and GetDistance(es.target)>250 and not haveYDW(es.target) then
                                        local dashPos = myHero + (Vector(ts.target) - myHero):normalized()*eRange
                                        if not inTurretRange(dashPos) then
                                                CastE(es.target)
                                        end
                                elseif GetDistance(es.target) <= 2500 then
                                        eGapClose(es.target)
                                end
                        end
                elseif EREADY and es.target and es.target.type == myHero.type and GetDistance(es.target) <= 1200 then
                        eGapClose(es.target)
                end
	 else
		if YasuoConfig.Auto.autoQ then
			if ts.target and ts.target.type == myHero.type then CastQ(ts.target) end
		end
		
		if YasuoConfig.Auto.qStacker then
			getJumpable()
			for i, enemy in pairs(JumpableEnemies) do
				if enemy and not enemy.dead and enemy.visible then
					if not knockUpReady and GetDistance(enemy) < qRange1 then
						CastQ(enemy)
					end
				end
			end
		end
		
		if YasuoConfig.Farm.eFarm then
			eFarm()
		end
		
	 end
	 if castQforSweep then
		qAfterSwipe()
	 end
	 if YasuoConfig.Auto.rEnemies ~= 0 and checkKnockedChemps()>=YasuoConfig.Auto.rEnemies  then
		CastR()
	 end
	
end

function OnLoad()
	ignite = (player:GetSpellData(SUMMONER_1).name == "SummonerDot" and SUMMONER_1 or (player:GetSpellData(SUMMONER_2).name == "SummonerDot" and SUMMONER_2 or nil))
    barrier = (player:GetSpellData(SUMMONER_1).name == "SummonerBarrier" and SUMMONER_1 or (player:GetSpellData(SUMMONER_2).name == "SummonerBarrier" and SUMMONER_2 or nil))
	ts  = TargetSelector(TARGET_LOW_HP_PRIORITY, 500, DAMAGE_PHYSICAL, false)
	es = TargetSelector(TARGET_LOW_HP_PRIORITY, 2300, DAMAGE_PHYSICAL, true)
	createMenu()

	
	-->SetupEnemies<---
	EnemyTable = GetEnemyHeroes()
	EnemyMinions = minionManager(MINION_ENEMY, 1300, myHero)
	JungleMinions = minionManager(MINION_JUNGLE, 1300, myHero)
	getJumpable()
	towersUpdate()
	-->SetupEnemies<---
	
	--arrangePrioritys()
	Prodiction = ProdictManager.GetInstance()
	qp = Prodiction:AddProdictionObject(_Q, 450, 1800, 0.25, 50)
    qp2 = Prodiction:AddProdictionObject(_Q, 900, 1200, 0.25, 50)

	arrangePrioritys()
	 
	PrintChat("<font color='#CCCCCC'> >> Yasuo - The God of LateGame! <<</font>")
end

------------------------------Skills--------------------------------
function CastQ(target)
	if  target.type == myHero.type then
		local qPred = (knockUpReady and qp2:GetPrediction(target)) or qp:GetPrediction(target)
		if qPred and GetDistance(qPred) <= qRange then CastSpell(_Q, qPred.x, qPred.z) end
	else
		CastSpell(_Q, target.x, target.z)
	end
end

function CastW()

end

function CastE(target)
	CastSpell(_E, target)
end

function CastR()
	CastSpell(_R)
end
------------------------------Skills--------------------------------


-------------------------Summoner spells----------------------------
 function SummonerOnTick()
        if ignite and YasuoConfig.Summ.autoIgnite and myHero:CanUseSpell(ignite) == READY then
                for _, enemy in pairs(EnemyTable) do
                        if ValidTarget(enemy, 600) and enemy.health <= 50 + (20 * player.level) then
                                CastSpell(ignite, enemy)
                        end
                end
        end
        if barrier and YasuoConfig.Summ.autoBarrier and myHero:CanUseSpell(barrier) == READY then
                if GetTickCount() >= nextCheck then
                        local co = ((myHero.health / myHero.maxHealth * 100) - 20)*(0.3-0.1)/(100-20)+0.1
                        local proc = myHero.maxHealth * co
                        if healthBefore - myHero.health > proc and myHero.health < myHero.maxHealth * 0.3 then
                                CastSpell(barrier)
                        end
                        nextCheck = GetTickCount() + 100
                        if GetTickCount() >= nextUpdate then
                                healthBefore = myHero.health
                                healthBeforeTimer = GetTickCount()
                                nextUpdate = GetTickCount() + 1000
                        end
                end
        end
end
-------------------------Summoner spells----------------------------


----------------------- Items Use------------------------------------
 function UseItemsOnTick()
	if ts.target then
		for _,item in pairs(items) do
			item.slot = GetInventorySlotItem(item.id)
			if item.slot ~= nil then
				if item.reqTarget and GetDistance(ts.target) <= item.range and item.menu ~= "BRK" then
					CastSpell(item.slot, ts.target)
				elseif item.reqTarget and GetDistance(ts.target) <= item.range and item.menu == "BRK" then
					if myHero.health <= myHero.maxHealth*0.65 or GetDistance(ts.target) > 400 then
						CastSpell(item.slot, ts.target)
					end
				elseif not item.reqTarget then
					CastSpell(item.slot)
				end
			end
		end
	end
end
-------------------------Items Use -----------------------------------------


------------------Priority Setting-------------
function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
			return
        end
    end
end
 
function arrangePrioritys()
    for i, enemy in ipairs(EnemyTable) do
        SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP,       enemy, 2)
        SetPriority(priorityTable.Support,  enemy, 3)
        SetPriority(priorityTable.Bruiser,  enemy, 4)
        SetPriority(priorityTable.Tank,     enemy, 5)
    end
end
------------------Priority Setting-------------------


--------------------Calculations---------------------
function findClosestPoint(target)
		getJumpable()
        local closestPoint = nil
        local currentPoint = nil
        for i, minion in pairs(JumpableEnemies) do
                if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange then
                        currentPoint = myHero + (Vector(minion) - myHero):normalized()*eRange
                        if closestPoint == nil then
                                closestPoint = currentPoint
                                closestMinion = minion
                        elseif GetDistance(currentPoint, target) < GetDistance(closestPoint, target) then
                                closestPoint = currentPoint
                                closestMinion = minion
                        end
                end
        end
        return closestPoint, closestMinion
end

function inTurretRange(pos)
        --[[if YasuoConfig.Basic.ignoreTowers then return false end
		--local check = false
        for i, tower in ipairs(towers) do
                if tower and (tower.health > 0 or not tower.dead) then
                        if GetDistance(tower, pos) <= 890 then return true end
                else
                        table.remove(towers, i)
                end
        end]]
        return false
end

function checkKnockedChemps()
	local chempCount = 0
	for i,enChemp in pairs(EnemyTable) do
		if enChemp ~= nil and enChemp.y>70 and GetDistance(enChemp) < 1300 and not inTurretRange(enChemp) then
			chempCount = chempCount + 1
		end
	end
	return chempCount
end


--------------------Calculations---------------------


--------------------CallBacks------------------------

function OnLoseBuff(unit, buff)
    if unit.isMe and buff.name == "yasuoq3w" then knockUpReady = false end
end

function OnGainBuff(unit, buff)
	if unit.isMe and buff.name == "yasuoq3w" then knockUpReady = true end
end

function OnAnimation(unit, animationName)
    if unit.isMe and animationName == "Spell3" then dashing = true 
	else dashing = false end
end

function OnDraw()
	if not YasuoConfig.General.noDraw then
		DrawCircle(myHero.x, myHero.y, myHero.z, 285, 0x00FF00)	
		DrawCircle(myHero.x, myHero.y, myHero.z, 475, 0x0000FF)	
	end
end
--------------------CallBacks------------------------

-------------------Action functions------------------

function attackEnemy(enemy) 
    if enemy.dead or not enemy.valid then return false end
	myHero:Attack(enemy)
	return true
end

function qAfterSwipe()
	if myHero:CanUseSpell(_Q) == READY then
		if castQforSweep and GetDistance(dPosQ,myHero) > 220 then
			CastSpell(_Q, myHero.x+20,myHero.z+30)
			castQforSweep = false
		end
	else
		castQforSweep = false
	end
end	


function getJumpable()
	for k,v in pairs(JumpableEnemies) do JumpableEnemies[k]=nil end
	EnemyMinions:update()
	JungleMinions:update()
	for i, minion in pairs(EnemyMinions.objects) do
		if minion ~= nil then table.insert(JumpableEnemies,minion) end
	end
	for i, jungM in pairs(JungleMinions.objects) do
		if jungM ~= nil then  table.insert(JumpableEnemies,jungM) end
	end
	for i, enhero in pairs(EnemyTable) do
		if enhero ~= nil then  table.insert(JumpableEnemies,enhero) end
	end
	--print(#JumpableEnemies)
end

function eGapClose(target)
    local closestPoint, closestMinion = findClosestPoint(target)
    if closestPoint and not inTurretRange(closestPoint) and (GetDistance(closestPoint, target) < GetDistance(target)) and GetDistance(closestPoint, target) < 500 then
		CastE(closestMinion)
    end
end

function haveYDW(tgt)
	buffCount = tgt.buffCount
	for i = 1, buffCount, 1 do
		local buff = tgt:getBuff(i)
		if buff.valid and buff.name == "YasuoDashWrapper" then
			return true
		end
	end
	return false
end

function towersUpdate()
        for i = 1, objManager.iCount, 1 do
                local obj = objManager:getObject(i)
                if obj and obj.type == "obj_AI_Turret" and obj.health > 0 then
                        if not string.find(obj.name, "TurretShrine") and obj.team ~= player.team then
                             table.insert(towers, obj)
                        end
                end
        end
end

function eFarm()
		getJumpable()
        for i, minion in pairs(JumpableEnemies) do
                if minion and minion.valid and not minion.dead and not haveYDW(minion) and GetDistance(minion) <= eRange+250 then
                        local dashPos = myHero + (Vector(minion) - myHero):normalized()*eRange
						if not inTurretRange(dashPos) then
							if YasuoConfig.Farm.eFarmCheckHealth and minion.health+7 < getDmg("E", minion, myHero) then
								CastE(minion)
							elseif not YasuoConfig.Farm.eFarmCheckHealth  then
								CastE(minion)
								dPosQ.x = myHero.x
								dPosQ.y = myHero.y
								dPosQ.z = myHero.z
								castQforSweep = true
							elseif YasuoConfig.Farm.eFarmCheckHealth and minion.health-100 > (myHero.damage + myHero.addDamage) then
								attackEnemy(minion)
							elseif not YasuoConfig.Farm.eFarmCheckHealth then
								attackEnemy(minion)
							end
							break 
						end
                end
        end
		--hitMinion()
end

-------------------Action functions------------------



function createMenu()
	 YasuoConfig = scriptConfig("Yasuo - The God of LateGame", "Yasuo_The_Unforgiven")
	 --> General
	 YasuoConfig:addSubMenu("General Settings", "General")
	 YasuoConfig.General:addParam("noDraw", "No drawings", SCRIPT_PARAM_ONOFF, false)
     --> Basic
     YasuoConfig:addSubMenu("Basic Settings", "Basic")
	 YasuoConfig.Basic:addParam("KillDemAll", "Kill dem ALL", SCRIPT_PARAM_ONKEYDOWN, false, 32)---< Working on it
	 YasuoConfig.Basic:addParam("ignoreTowers", "Ignore - Turrets", SCRIPT_PARAM_ONOFF, false)
	 --> Auto
     YasuoConfig:addSubMenu("Auto Settings", "Auto")        
	 YasuoConfig.Auto:addParam("autoQ", "Steel Tempest - Auto Poke Enemy Heroes", SCRIPT_PARAM_ONOFF, false)
     YasuoConfig.Auto:addParam("qStacker", "Steel Tempest - Auto Stack", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
	 YasuoConfig.Auto:addParam("rEnemies", "Last Breath (0-disable)",SCRIPT_PARAM_SLICE, 3, 0, 5, 0)
	 --> Summoner
	YasuoConfig:addSubMenu("Summoner Settings", "Summ")
	if ignite ~= nil then
		YasuoConfig.Summ:addParam("autoIgnite", "Use Ignite if killable", SCRIPT_PARAM_ONOFF, false)
	end
	if barrier ~= nil then
		YasuoConfig.Summ:addParam("autoBarrier", "Use Shield on low hp", SCRIPT_PARAM_ONOFF, false)
	end
	--> Farm
    YasuoConfig:addSubMenu("Farm Settings", "Farm")
    YasuoConfig.Farm:addParam("eFarm", "Farm - Sweeping Blade (G)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("G"))
	YasuoConfig.Farm:addParam("eFarmCheckHealth", "Farm - Sweeping Blade Only lasthit", SCRIPT_PARAM_ONOFF,true)
	YasuoConfig.Farm:addParam("eFarmUseQ", "Farm - Sweeping Blade Uses Q", SCRIPT_PARAM_ONOFF,false)
	YasuoConfig.Farm:permaShow("eFarm")
	YasuoConfig.Auto:permaShow("qStacker")
	YasuoConfig.Basic:permaShow("KillDemAll")
end

-->Our data arrays

--From AutoCarry Ty

--[[ Items ]]--
local items =
	{
		{name = "Blade of the Ruined King", menu = "BRK", id=3153, range = 450, reqTarget = true, slot = nil },
		{name = "Bilgewater Cutlass", menu = "BWC", id=3144, range = 450, reqTarget = true, slot = nil },
		{name = "Deathfire Grasp", menu = "DFG", id=3128, range = 750, reqTarget = true, slot = nil },
		{name = "Hextech Gunblade", menu = "HGB", id=3146, range = 400, reqTarget = true, slot = nil },
		{name = "Ravenous Hydra", menu = "RSH", id=3074, range = 350, reqTarget = false, slot = nil},
		{name = "Sword of the Divine", menu = "STD", id=3131, range = 350, reqTarget = false, slot = nil},
		{name = "Tiamat", menu = "TMT", id=3077, range = 350, reqTarget = false, slot = nil},
		{name = "Entropy", menu = "ETR", id=3184, range = 350, reqTarget = false, slot = nil},
		{name = "Youmuu's Ghostblade", menu = "YGB", id=3142, range = 350, reqTarget = false, slot = nil}
	}


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
        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "KogMaw", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir","Jinx",
        "Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed","Lucian",
 
    },
 
    Bruiser = {
        "Aatrox", "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",
        "Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao","Yasuo",
    },
 
}

		