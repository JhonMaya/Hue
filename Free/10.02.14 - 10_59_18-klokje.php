<?php exit() ?>--by klokje 145.93.248.90
--[[ Settings and Global Variables ]]--
local VERSION = "3.0"

-- Constants (do not change)
local GLOBAL_RANGE = 0
local NO_RESOURCE = 0
local DEFAULT_STARCALL_MODE = 3
local DEFAULT_STARCALL_MIN_MANA = 300 --Starcall will not be cast if mana is below this level
local DEFAULT_NUM_HIT_MINIONS = 3 -- number of minions that need to be hit by starcall before its cast
local DEFAULT_HEAL_MODE = 2
local DEFAULT_HEAL_THRESHOLD = 75 -- for healMode 3, default 75 (75%)
local DEFAULT_INFUSE_MODE = 2 
local DEFAULT_MIN_ALLY_SILENCE = 70 -- percentage of mana nearby ally lolshould have before soraka uses silence
local DEFAULT_ULT_MODE = 3
local DEFAULT_ULT_THRESHOLD = 35 --percentage of hp soraka/ally/team must be at or missing for ult, eg 10 (10%)
local DEFAULT_DENY_THRESHOLD = 75
local DEFAULT_STEAL_THRESHOLD = 60
local MAX_PLAYER_AA_RANGE = 850

-- Auto Level
LevelSequence = {_W,_E,_Q,_W,_W,_R,_W,_E,_W,_E,_R,_E,_E,_Q,_Q,_R,_Q,_Q} -- order to level abilities
SorakaLevel = 0

-- Auto Heal (W) - Soraka heals the nearest injured ally champion
rawHealAmount = {70, 120, 170, 220, 270}
rawHealRatio = 0.35
HEAL_RANGE = 750

--[[ healMode notes:
1 = heal if nearest ally is missing any health, [Normal]
2 = heal if nearest ally is missing as much health as the heal (ie so none of the heal will be wasted) [Smart]
3 = heal if nearest ally is below 'healThreshold'. Ie ally is below 0.75 (75%) of their full hp. [Threshold]
-]]

--Auto StarCall (Q)
STARCALL_RANGE = 675

--[[ starcallMode notes:
1 = use only when at least one enemy will be hit by starcall [Harass only]
2 = use only when at least X minions will be hit by starcall (enemy champions may be hit if in range) {Farm/Push only]
3 = use only when at at least one enemy OR at least X minions will be hit by starcall [Both of the above, hit any]
4 = use only when at at least one enemy AND at least X minions will be hit by starcall [Both of the above, hit enemy and minions]
-]]

--Auto Infuse Ally (E) - Gives mana back to ally
rawInfuseAmount = {20, 40, 60, 80, 100}
rawInfuseRatio = 0.05 -- of maximum mana

INFUSE_RANGE = 725

--[[ infuseMode notes:
1 = provide mana to the most mana deprived ally if they are missing any mana
2 = provide mana to the most mana deprived ally if they yare missing as much mana as the restore amount
-]]

--Auto Silence Enemy (E) - Silences an enemy unit
-- Note: SILENCE_RANGE = INFUSE_RANGE (same range)

-- Auto Ultimate
rawUltAmount = {150, 250, 350}
rawUltatio = 0.55 -- of AP

--[[ ultMode notes:
1 = ult when Soraka is low/about to die, under ultThreshold% of hp [selfish ult]
2 = ult when ally is low/about to die, under ultThreshold% of hp [lane partner ult]
3 = ult when total missing health of entire team exceeds ultThreshold (ie 50% of entire team health is missing)
-]]
				 
--[[ Main Functions ]]--

-- Soraka performs starcall to help push/farm a lane or harrass enemy champions (or both)
function doSorakaStarcall()
	-- Perform Starcall based on starcallMode
	local hitEnemy = false
	local hitMinions = false
	
	-- Calculations
	local enemy = GetPlayer(TEAM_ENEMY, false, false, player, STARCALL_RANGE, NO_RESOURCE)
	
	if enemy ~= nil then hitEnemy = true end
	
	-- Minion Calculations
	enemyMinions:update()
	local totalMinionsInRange = 0
	
	for _, minion in pairs(enemyMinions.objects) do
		if player:GetDistance(minion) < STARCALL_RANGE then
			totalMinionsInRange = totalMinionsInRange + 1
		end
	
		if totalMinionsInRange >= config.autoStarcall.numOfHitMinions then 
			hitMinions = true
			break 
		end
	end
		
	if config.autoStarcall.starcallMode == 1 and hitEnemy then
		CastSpell(_Q)
	elseif config.autoStarcall.starcallMode == 2 and hitMinions then 
		CastSpell(_Q)
	elseif config.autoStarcall.starcallMode == 3 and (hitEnemy or hitMinions) then
		CastSpell(_Q)
	elseif config.autoStarcall.starcallMode == 4 and (hitEnemy and hitMinions) then
		CastSpell(_Q)
	end
end

-- Soraka Heals the nearby most injured ally or herself, assumes heal is ready to be used
function doSorakaHeal()
	-- Find ally champion to heal
	local ally = GetPlayer(player.team, false, true, player, HEAL_RANGE, "health")
	
	-- If no eligable ally, return
	if ally == nil then return end
	
	-- Heal ally based on healmode
	if config.autoHeal.healMode == 1 then
		if ally.health < ally.maxHealth then
			CastSpell(_W, ally)
		end
	elseif config.autoHeal.healMode == 2 then
		local totalHealAmount = rawHealAmount[player:GetSpellData(_W).level] + (rawHealRatio * player.ap)
		totalHealAmount = calcSalvation(totalHealAmount, ally.health, ally.maxHealth)
		
		
		if ally.health < (ally.maxHealth - totalHealAmount) then
			CastSpell(_W, ally)
		end
	elseif config.autoHeal.healMode == 3 then
		if (ally.health/ally.maxHealth) < (config.autoHeal.healThreshold / 100) then
			CastSpell(_W, ally)
		end
	end
end

-- Soraka uses ultimate based on user preference
function doSorakaUlt()
	-- Ult based on ultMode
	if config.autoUlt.ultMode == 1 then
		if (player.health/player.maxHealth) < (config.autoUlt.ultThreshold / 100) then
			CastSpell(_R)
		end
	elseif config.autoUlt.ultMode == 2 then
		-- Find nearby ally champion (your lane partner usually) that is fatally injured
		
		local ally = GetPlayer(player.team, false, false, nil, GLOBAL_RANGE, "health")
		
		-- Use ult if suitable ally found
		if ally ~= nil and (ally.health/ally.maxHealth) < (config.autoUlt.ultThreshold / 100) then
			CastSpell(_R)
		end
	elseif config.autoUlt.ultMode == 3 then
		--find total hp of team as a percentage, ie team had 40% of their max hp
		local totalMissingHP = 0
		local counter = 0
		
		for i=1, heroManager.iCount do
			local hero = heroManager:GetHero(i)
			
			if hero ~= nil and hero.type == "obj_AI_Hero" and hero.team == player.team and hero.dead == false then --checks for ally and that person is not dead
				totalMissingHP = totalMissingHP + (hero.health/hero.maxHealth)
				counter = counter + 1
			end
		end
		
		totalMissingHP = totalMissingHP / counter
		
		if totalMissingHP < (config.autoUlt.ultThreshold / 100) then
			CastSpell(_R)
		end
	end
end

-- Soraka Infuses the most mana deprived ally giving them mana and armor
function doSorakaInfuse()
	-- Find ally champion to infuse
	local ally = GetPlayer(player.team, false, false, player, INFUSE_RANGE, "mana")
	
	-- Infuse ally based on infuseMode
	if ally ~= nil then
		if config.autoInfuse.infuseMode == 1 then
			if ally.mana < ally.maxMana then
				CastSpell(_E, ally)
			end
		elseif config.autoInfuse.infuseMode == 2 then
			local totalInfuseAmount = rawInfuseAmount[player:GetSpellData(_E).level] + (rawInfuseRatio * player.maxMana)
			totalInfuseAmount = calcSalvation(totalInfuseAmount, ally.mana, ally.maxMana)
			
			if ally.mana < (ally.maxMana - totalInfuseAmount) then
				CastSpell(_E, ally)
			end
		end
	end
end

-- Soraka silences an enemy if they get too close to a nearby ally OR soraka
function doSorakaSilence()
	-- Find enemy to silence
	local silenceTarget = GetPlayer(TEAM_ENEMY, false, false, player, INFUSE_RANGE, NO_RESOURCE)
		
	if silenceTarget ~= nil then
		CastSpell(_E, silenceTarget)
	end
end

-- Deny cannon minion farm by healing it 
function doDenyFarm()
	-- Get Cannon Minion
	cannonMinionDeny:update()
	
	local targetCannonMinion = nil
	
	for _, minion in pairs(cannonMinionDeny.objects) do
		if minion.dead == false and (minion.charName == "Blue_Minion_MechCannon" or minion.charName == "Red_Minion_MechCannon") then
			targetCannonMinion = minion
		end
	end
	
	-- If minion found
	if targetCannonMinion ~= nil then
	
		-- Find Nearby Enemy with highest AD (assumption: adc total AD > support total AD)
		local enemy = GetPlayer(TEAM_ENEMY, false, false, targetCannonMinion, MAX_PLAYER_AA_RANGE, "AD") 
		
			if enemy ~= nil then
				local enemyDamage = getDmg("AD", targetCannonMinion, enemy)
				
				-- Heal cannon if in range and may prevent a last hit
				if targetCannonMinion.health < enemyDamage and player:GetDistance(targetCannonMinion) < HEAL_RANGE then
					CastSpell(_W, targetCannonMinion)
				end
			
			end
	end
	
end

-- Steal cannon minion farm by infusing it
-- If no minion is in range or minion dies, will call decideE with true skipSteal so that E can be used for something else
function doStealFarm()
	-- Get Cannon Minion
	cannonMinionSteal:update()
	
	local targetCannonMinion = nil
	
	for _, minion in pairs(cannonMinionSteal.objects) do
		if minion.dead == false and (minion.charName == "Blue_Minion_MechCannon" or minion.charName == "Red_Minion_MechCannon") then
			targetCannonMinion = minion
		end
	end
	
	-- If minion found
	if targetCannonMinion ~= nil then
	
		-- Check if infuse will do enough damage to steal it
		local infuseDamage = getDmg("E", targetCannonMinion, player)
		
		-- If target is stealable and in range, infuse it
		if targetCannonMinion.health < infuseDamage and player:GetDistance(targetCannonMinion) < INFUSE_RANGE then
			CastSpell(_E, targetCannonMinion)
		end
	-- If minion is dead or not in range, then use E for something else
	elseif targetCannonMinion == nil or player:GetDistance(targetCannonMinion) > INFUSE_RANGE then
		decideE(true)
	end
end

--[[ Helper Functions ]]--

-- Decides whether to use W to heal or to deny farm
function decideW()
	-- See if there are any allies nearby
	local ally = GetPlayer(player.team, false, true, player, HEAL_RANGE, "health")
	
	-- If ally or Soraka needs health, then heal them
	if ally ~= nil and (ally.health/ally.maxHealth) < (config.denyStealFarm.denyThreshold / 100) then
		doSorakaHeal()
	else -- Otherwise, deny minion farm
		doDenyFarm()
	end
end

-- Decides whether to use E defensively or use E offensively
function decideE(skipSteal)
	-- See if there are any allies nearby
	local ally = GetPlayer(player.team, false, false, player, INFUSE_RANGE, "mana")
	
	-- If ally needs mana, then infuse defensively, otherwise steal or silence
	if (ally == nil or (ally.mana/ally.maxMana) > (config.denyStealFarm.stealThreshold / 100)) and config.denyStealFarm.stealEnabled and (skipSteal == nil or skipSteal == false) then
		doStealFarm()
	elseif (ally == nil or (ally.mana/ally.maxMana) > (config.autoSilence.minAllyManaForSilence / 100)) and config.autoSilence.enabled then
		doSorakaSilence()
	elseif ally ~= nil and config.autoInfuse.enabled then
		doSorakaInfuse()
	end
	
end

-- Returns correct restore amoune due to Soraka's passive
function calcSalvation(totalRestoreAmount, targetCurResource, targetMaxResource)
	local salvationFactor = ((1 - (targetCurResource/targetMaxResource)) / 2) + 1
	
	return totalRestoreAmount * salvationFactor
end

--[[ Helper Functions ]]--
-- Return player based on their missing health or mana
function GetPlayer(team, includeDead, includeSelf, distanceTo, distanceAmount, resource)
	local target = nil
	
	for i=1, heroManager.iCount do
		local member = heroManager:GetHero(i)
		
		if member ~= nil and member.type == "obj_AI_Hero" and member.team == team and (member.dead ~= true or includeDead) then
			if member.charName ~= player.charName or includeSelf then
				if distanceAmount == GLOBAL_RANGE or member:GetDistance(distanceTo) <= distanceAmount then
					if target == nil then target = member end
					
					if resource == "health" then --least health
						if member.health < target.health then target = member end
					elseif resource == "mana" then --least mana
						if member.mana < target.mana then target = member end
					elseif resource == "AD" then --highest AD
						if member.totalDamage > target.totalDamage then target = member end
					elseif resource == NO_RESOURCE then
						return member -- as any member is eligible
					end
				end
			end
		end
	end
	
	return target
end

--[[ OnTick ]]--
function OnTick()
	-- Check if script should be run
	if not config.enableScript then return end
	
	-- Auto Level
	if config.autoLevel and player.level > SorakaLevel then
		LevelSpell(LevelSequence[player.level])
		SorakaLevel = player.level
	end
	
	-- Auto Ult (R)
	if config.autoUlt.enabled and player:CanUseSpell(_R) == READY then
		doSorakaUlt()
	end
	
	-- Only perform following tasks if not in fountain 
	if not InFountain() then
		-- Auto Heal and Deny Farm (W)
		if player:CanUseSpell(_W) == READY then
			if config.autoHeal.enabled and not config.denyStealFarm.denyEnabled then
				doSorakaHeal()
			elseif not config.autoHeal.enabled and config.denyStealFarm.denyEnabled then
				doDenyFarm()
			elseif config.autoHeal.enabled and config.denyStealFarm.denyEnabled then
				decideW()
			end
		end
		
		-- Auto Infuse Ally and Auto Silence Enemy (E)
		if player:CanUseSpell(_E) == READY then
			-- If at least one E option is enabled, decide E
			if not (not config.autoInfuse.enabled and not config.autoSilence.enabled and not config.denyStealFarm.stealEnabled) then
				decideE()
			end
		end
		
		-- Auto StarCall (Q)
		if config.autoStarcall.enabled and player:CanUseSpell(_Q) == READY and player.mana > config.autoStarcall.starcallMinMana then
			doSorakaStarcall()
		end
		
		doStealFarm()
	end
end

--[[ OnWndMsg ]]--
function OnWndMsg(msg, key)
	-- to be added
end

--[[ OnLoad ]]--
function OnLoad()
	player = GetMyHero()

	PrintChat("All-In-One Soraka V"..VERSION.." by OneTM loaded.")	
	
	-- Config Menu
	config = scriptConfig("All-In-One Soraka", "All-In-One Soraka")	

	config:addParam("enableScript", "Enable Script", SCRIPT_PARAM_ONOFF, true)
	config:addParam("autoLevel", "Auto Level", SCRIPT_PARAM_ONOFF, true)
	
	config:addSubMenu("Auto Heal", "autoHeal")
	config:addSubMenu("Auto Starcall", "autoStarcall")
	config:addSubMenu("Auto Infuse", "autoInfuse")
	config:addSubMenu("Auto Silence", "autoSilence")
	config:addSubMenu("Auto Ult", "autoUlt")
	config:addSubMenu("Deny/Steal Farm", "denyStealFarm")
	
	config.denyStealFarm:addParam("denyEnabled", "Deny Cannon Minions (W)", SCRIPT_PARAM_ONOFF, false)
	config.denyStealFarm:addParam("stealEnabled", "Steal Cannon Minions (E)", SCRIPT_PARAM_ONOFF, false)
	config.denyStealFarm:addParam("denyThreshold", "Deny Health Threshold (%)", SCRIPT_PARAM_SLICE, DEFAULT_DENY_THRESHOLD, 0, 100, 0)
	config.denyStealFarm:addParam("stealThreshold", "Steal Mana Threshold (%)", SCRIPT_PARAM_SLICE, DEFAULT_STEAL_THRESHOLD, 0, 100, 0)
	
	config.autoHeal:addParam("enabled", "Enable", SCRIPT_PARAM_ONOFF, true)
	config.autoHeal:addParam("healMode", "Heal Mode", SCRIPT_PARAM_LIST, DEFAULT_HEAL_MODE, { "Normal", "Smart", "Threshold" })
	config.autoHeal:addParam("healThreshold", "Heal Threshold (%)", SCRIPT_PARAM_SLICE, DEFAULT_HEAL_THRESHOLD, 0, 100, 0)
	
	config.autoStarcall:addParam("enabled", "Enable", SCRIPT_PARAM_ONOFF, true)
	config.autoStarcall:addParam("starcallMode", "Starcall Mode", SCRIPT_PARAM_LIST, DEFAULT_STARCALL_MODE, { "Harass Only", "Farm/Push", "Both (hit any)", "Both (hit enemy and minions)" })
	config.autoStarcall:addParam("starcallMinMana", "Starcall Minimum Mana", SCRIPT_PARAM_SLICE, DEFAULT_STARCALL_MIN_MANA, 50, 500, 0)
	config.autoStarcall:addParam("numOfHitMinions", "Minimum Hit Minions", SCRIPT_PARAM_SLICE, DEFAULT_NUM_HIT_MINIONS, 1, 10, 0)
	
	config.autoInfuse:addParam("enabled", "Enable", SCRIPT_PARAM_ONOFF, true)
	config.autoInfuse:addParam("infuseMode", "Infuse Mode", SCRIPT_PARAM_LIST, DEFAULT_INFUSE_MODE, { "Normal", "Smart"})
	
	config.autoSilence:addParam("enabled", "Enable", SCRIPT_PARAM_ONOFF, true)
	config.autoSilence:addParam("minAllyManaForSilence", "Min Ally Mana for Silence (%)", SCRIPT_PARAM_SLICE, DEFAULT_MIN_ALLY_SILENCE, 0, 100, 0)
	
	config.autoUlt:addParam("enabled", "Enable", SCRIPT_PARAM_ONOFF, true)
	config.autoUlt:addParam("ultMode", "Ultimate Mode", SCRIPT_PARAM_LIST, DEFAULT_ULT_MODE, { "Selfish", "Lane Partner", "Entire Team" })
	config.autoUlt:addParam("ultThreshold", "Ult Threshold (%)", SCRIPT_PARAM_SLICE, DEFAULT_ULT_THRESHOLD, 0, 100, 0)
	
	-- Setup minion manager
	enemyMinions = minionManager(MINION_ENEMY, STARCALL_RANGE, player, MINION_SORT_HEALTH_ASC) -- for starcall
	
	cannonMinionDeny = minionManager(MINION_ALLY, HEAL_RANGE, player, MINION_SORT_MAXHEALTH_DEC) -- for deny farm
	cannonMinionSteal = minionManager(MINION_ENEMY, INFUSE_RANGE, player, MINION_SORT_MAXHEALTH_DEC) -- for steal farm
end