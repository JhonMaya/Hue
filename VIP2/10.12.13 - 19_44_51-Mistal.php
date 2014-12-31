<?php exit() ?>--by Mistal 83.217.129.57
--		MordGameChanger Combo

--width = player:GetSpellData(_Q).lineWidth
--speed = player:GetSpellData(_Q).missileSpeed
--delay = (1 + player:GetSpellData(_Q).delayCastOffsetPercent ) * 500

if myHero.charName ~= "Mordekaiser" then return end

local qRange, wRange, eRange, rRange= 100, 750, 700, 850

if VIP_USER then
	
	require 'Prodiction'
	
	Prod = ProdictManager.GetInstance()
	ep = Prod:AddProdictionObject(_E, eRange+50, 1500, 0.250, 650)   -- (spell, range, missilespeed, delay, width (cone))
	PrintChat("MordGameChanger (PROdiction) Combo loaded!")
else
	ep = TargetPrediction(eRange, 1.5, 0.25)
	PrintChat("MordGameChanger Combo loaded!")
end	

function OnLoad()
	comboMenu()
	
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, eRange+50, DAMAGE_MAGIC)
	--rs = TargetSelector(TARGET_LOW_HP, 1450, DAMAGE_MAGIC)
	ts.name = "Ability Target"
	MordCombo:addTS(ts)
	
	--> Ignite
	igniteSlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
end

function OnTick()
	Checks()
	if MordCombo.scriptActive and ts.target then
		itemTick(ts.target)
		if WREADY and EREADY then castW(ts.target) end
		if EREADY then castE(ts.target) end
		if QREADY then castQ(ts.target) end
	end
	if MordCombo.Eharass and ts.target then 
		if EREADY then castE(ts.target) end
	end
	if MordCombo.autoIgnite then autoIgnite() end
	if MordCombo.autoUlt then autoUlt() end
end

function OnDraw()
	if not myHero.dead then
			DrawCircle(myHero.x, myHero.y, myHero.z, eRange, 0x00FFFF)
			DrawCircle(myHero.x, myHero.y, myHero.z, rRange, 0x00FF00)
	end
end

--> Mace of Spades
function castQ(target)
	if target and GetDistance(target) <= qRange then
				CastSpell(_Q)
				myHero:Attack(target)
	end 
end

--> Creeping Death
function castW(target)
	if target and GetDistance(target) <= 250 then
		CastSpell(_W)
	end
end

--> Siphon of Destruction
function castE(target)
	
	if VIP_USER then
		--predictedE = ep:GetPrediction(target)
		if predictedE and GetDistance(target) <= eRange then
			CastSpell(_E, predictedE.x, predictedE.z)
		end
	end
	--CastSpell(_E, ePred.x, ePred.z) end
	--end
end

--> Stranglethorns Cast
function castR(target)
	if GetDistance(target) <= rRange then
		CastSpell(_R, target)
	end
end

--> Rise of Thorns Cast
--function castP(target)
--	if pPred and GetDistance(pPred) <= pRange then
--		if myHero:GetSpellData(_Q).name == myHero:GetSpellData(_W).name or myHero:GetSpellData(_W).name == myHero:GetSpellData(_E).name then
--			if VIP_USER then
--				if pp:GetHitChance(target) >= ZyraConfig.hitChance/100 then
--					CastSpell(_Q, pPred.x, pPred.z)
--				end
--			else CastSpell(_Q, pPred.x, pPred.z) end
--		end
--	end
--end

--> Children of the Grave
function autoUlt()
	for i, enemy in ipairs(GetEnemyHeroes()) do
	
	-- First check ign+ult
		if enemy and not enemy.dead and igniteSlot and RREADY and ignREADY and GetDistance(enemy) <= 600 and enemy.health < (getDmg("IGNITE", enemy, myHero) + getDmg("R", enemy, myHero)) then
			CastSpell(igniteSlot, enemy)
			castR(enemy)
		end
	-- Second check ult
		if enemy and not enemy.dead and RREADY and enemy.health < getDmg("R", enemy, myHero) then
			castR(enemy)
		end
	end
end

-- Auto Ignite
function autoIgnite()
	ignREADY = (myHero:CanUseSpell(igniteSlot) == READY)
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if igniteSlot and GetDistance(enemy) <= 600 and enemy.health < getDmg("IGNITE", enemy, myHero) then
			CastSpell(igniteSlot, enemy)
		end
	end
end

-- Combo Menu
function comboMenu()
	MordCombo = scriptConfig("MordGameChanger", "MordComboMenu")
	MordCombo:addParam("scriptActive", "Full Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	MordCombo:addParam("Eharass", "Siphon of Destruction Harass", SCRIPT_PARAM_ONKEYDOWN, false, 84)
	if VIP_USER then
		MordCombo:addParam("hitChance", "Ability Hitchance", SCRIPT_PARAM_SLICE, 60, 0, 100, 0)
	end
	MordCombo:addParam("autoIgnite", "Auto Ignite Killable", SCRIPT_PARAM_ONOFF, true)
	MordCombo:addParam("autoUlt", "Auto Ult Low HP", SCRIPT_PARAM_ONOFF, true)
end

-- Item Table
local items =
	{
		{name = "Deathfire Grasp", menu = "DFG", id=3128, range = 750, reqTarget = true, slot = nil },
		{name = "Hextech Gunblade", menu = "HGB", id=3146, range = 400, reqTarget = true, slot = nil },
	}

-- Item Casts
function itemTick(target)
	
	--for _,item in pairs(items) do
	dfgSlot = GetInventorySlotItem(3128)
	dfgReady = (dfgSlot ~= nil and GetInventoryItemIsCastable(3128,myHero))
  if dfgSlot ~= nil then
			if dfgReady and GetDistance(target) <= 750 then
				CastSpell(dfgSlot, target)
			end
	end
end

-- Checks
function Checks()
	ts:update()
	if ts.target then
		
		predictedE = ep:GetPrediction(ts.target)
	end
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	WREADY = (myHero:CanUseSpell(_W) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
end