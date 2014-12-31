<?php exit() ?>--by 149kg 81.166.153.19

--[[ Settings and Global Variables ]]--
version = 3
enableScript = true

-- Constants (do not change)
GLOBAL_RANGE = 0
NO_RESOURCE = 0
DEFAULT_STARCALL_MODE = 3
DEFAULT_HEAL_MODE = 2
DEFAULT_INFUSE_MODE = 2 
DEFAULT_ULT_MODE = 3

-- Auto Level
LevelSequence = {_W,_E,_Q,_W,_W,_R,_W,_E,_W,_E,_R,_E,_E,_Q,_Q,_R,_Q,_Q} -- order to level abilities
SorakaLevel = 0

-- Auto Heal (W) - Soraka heals the nearest injured ally champion
rawHealAmount = {70, 120, 170, 220, 270}
rawHealRatio = 0.35
healRange = 750
healLimit = 0.75  -- for healMode 3, default 0.75 (75%)

--[[ healMode notes:
1 = heal if nearest ally is missing any health, [Normal]
2 = heal if nearest ally is missing as much health as the heal (ie so none of the heal will be wasted) [Smart]
3 = heal if nearest ally is below 'healLimit'. Ie ally is below 0.75 (75%) of their full hp. [Threshold]
-]]

--Auto StarCall (Q)
starcallMinMana = 300 -- Starcall will not be cast if mana is below this level
starcallRange = 530
numOfHitMinions = 3 -- number of minions that need to be hit by starcall before its cast

--[[ starcallMode notes:
1 = use only when at least one enemy will be hit by starcall [Harass only]
2 = use only when at least X minions will be hit by starcall (enemy champions may be hit if in range) {Farm/Push only]
3 = use only when at at least one enemy OR at least X minions will be hit by starcall [Both of the above, hit any]
4 = use only when at at least one enemy AND at least X minions will be hit by starcall [Both of the above, hit enemy and minions]
-]]

--Auto Infuse Ally (E) - Gives mana back to ally
rawInfuseAmount = {20, 40, 60, 80, 100}
rawInfuseRatio = 0.05 -- of maximum mana
infuseRange = 725

--[[ infuseMode notes:
1 = provide mana to the most mana deprived ally if they are missing any mana
2 = provide mana to the most mana deprived ally if they yare missing as much mana as the restore amount
-]]

--Auto Silence Enemy (E) - Silences an enemy unit
-- Note: silenceRange = infuseRange (same range)
minAllyManaForSilence = 0.7 -- percentage of mana ally should have before soraka uses silence or E offensively

-- Auto Ultimate
rawUltAmount = {150, 250, 350}
rawUltatio = 0.55 -- of AP

ultThreshold = 0.45 --percentage of hp soraka/ally/team must be at or missing for ult, eg 0.10

--[[ ultMode notes:
1 = ult when Soraka is low/about to die, under ultThreshold% of hp [selfish ult]
2 = ult when ally is low/about to die, under ultThreshold% of hp [lane partner ult]
3 = ult when total missing health of entire team exceeds ultThreshold (ie 50% of entire team health is missing)
-]]

TriggerKey = 123 -- Key to trigger quote, default F12 (123), nil = always enabled
				 -- List of key codes: http://help.adobe.com/en_US/AS2LCR/Flash_10.0/help.html?content=00000520.html

				 
--[[ Custom Functions ]]--

-- Soraka performs starcall to help push/farm a lane or harrass enemy champions (or both)
function doSorakaStarcall()
	-- Perform Starcall based on starcallMode
	local hitEnemy = false
	local hitMinions = false
	
	-- Calculations
	local enemy = GetPlayer(TEAM_ENEMY, false, false, player, starcallRange, NO_RESOURCE)
	
	if enemy ~= nil then hitEnemy = true end
	
	-- Minion Calculations
	enemyMinions:update()
	local totalMinionsInRange = 0
	
	for j, minion in pairs(enemyMinions.objects) do
		if player:GetDistance(minion) < starcallRange then
			totalMinionsInRange = totalMinionsInRange + 1
		end
	
		if totalMinionsInRange >= numOfHitMinions then 
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
	local ally = GetPlayer(player.team, false, true, player, healRange, "health")
	
	-- If no eligable ally, return
	if ally == nil then return end
	
	-- Heal ally based on healmode
	if config.autoHeal.healMode == 1 then
		if ally.health < ally.maxHealth then
			CastSpell(_W, ally)
		end
	elseif config.autoHeal.healMode == 2 then
		local totalHealAmount = rawHealAmount[player:GetSpellData(_W).level] + (rawHealRatio * player.ap)
		
		if ally.health < (ally.maxHealth - totalHealAmount) then
			CastSpell(_W, ally)
		end
	elseif config.autoHeal.healMode == 3 then
		if (ally.health/ally.maxHealth) < healLimit then
			CastSpell(_W, ally)
		end
	end
end

-- Soraka uses ultimate based on user preference
function doSorakaUlt()
	-- Ult based on ultMode
	if config.autoUlt.ultMode == 1 then
		if (player.health/player.maxHealth) < ultThreshold then
			CastSpell(_R)
		end
	elseif config.autoUlt.ultMode == 2 then
		-- Find nearby ally champion (your lane partner usually) that is fatally injured
		
		local ally = GetPlayer(player.team, false, false, nil, GLOBAL_RANGE, "health")
		
		-- Use ult if suitable ally found
		if ally ~= nil and (ally.health/ally.maxHealth) < ultThreshold then
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
		
		if totalMissingHP < ultThreshold then
			CastSpell(_R)
		end
	end
end

-- Soraka Infuses the most mana deprived ally giving them mana and armor
function doSorakaInfuse()
	-- Find ally champion to infuse
	local ally = GetPlayer(player.team, false, false, player, infuseRange, "mana")
	
	-- Infuse ally based on infuseMode
	if ally ~= nil then
		if config.autoInfuse.infuseMode == 1 then
			if ally.mana < ally.maxMana then
				CastSpell(_E, ally)
			end
		elseif config.autoInfuse.infuseMode == 2 then
			local totalInfuseAmount = rawInfuseAmount[player:GetSpellData(_E).level] + (rawInfuseRatio * player.maxMana)
			
			if ally.mana < (ally.maxMana - totalInfuseAmount) then
				CastSpell(_E, ally)
			end
		end
	end
end

-- Soraka silences an enemy if they get too close to a nearby ally OR soraka
function doSorakaSilence()
	-- Find enemy to silence
	local silenceTarget = GetPlayer(TEAM_ENEMY, false, false, player, infuseRange, NO_RESOURCE)
		
	if silenceTarget ~= nil then
		CastSpell(_E, silenceTarget)
	end
end

-- Decides whether to use E defensively or use E offensively
function decideE()
	-- See if there are any allies nearby
	local ally = GetPlayer(player.team, false, false, player, infuseRange, "mana")
	
	-- If ally needs mana, then infuse defensively
	if ally ~= nil and (ally.mana/ally.maxMana) < minAllyManaForSilence then
		doSorakaInfuse()
	else -- Otherwise, silence enemy adc if no ally is in range or they have enough mana
		doSorakaSilence()
	end
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
					
					if resource == "health" then
						if member.health < target.health then target = member end
					elseif resource == "mana" then
						if member.mana < target.mana then target = member end
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
	
	-- Auto Heal (W)
	if config.autoHeal.enabled and player:CanUseSpell(_W) == READY then
		doSorakaHeal()
	end
	
	-- Auto Infuse Ally and Auto Silence Enemy (E)
	if player:CanUseSpell(_E) == READY then
		if config.autoInfuse.enabled and not config.autoSilence then
			doSorakaInfuse()
		elseif not config.autoInfuse.enabled and config.autoSilence then
			doSorakaSilence()
		elseif config.autoInfuse.enabled and config.autoSilence then
			decideE()
		end
	end
	
	-- Auto StarCall (Q)
	if config.autoStarcall.enabled and player:CanUseSpell(_Q) == READY and player.mana > starcallMinMana then
		doSorakaStarcall()
	end
	
	end -- Only perform above tasks if not at fountain
	
end

--[[ OnWndMsg ]]--
function OnWndMsg(msg, key)

end

--[[ OnLoad ]]--
function OnLoad()
	player = GetMyHero()

	PrintChat("All-In-One Soraka V"..version.." by OneTM loaded.")	
	
	-- Config Menu
	config = scriptConfig("All-In-One Soraka", "All-In-One Soraka")	
	
	config:addParam("enableScript", "Enable Script", SCRIPT_PARAM_ONOFF, true)
	config:addParam("autoLevel", "Auto Level", SCRIPT_PARAM_ONOFF, true)
	config:addParam("autoSilence", "Auto Silence", SCRIPT_PARAM_ONOFF, true)
	
	config:addSubMenu("Auto Heal", "autoHeal")
	config:addSubMenu("Auto Starcall", "autoStarcall")
	config:addSubMenu("Auto Infuse", "autoInfuse")
	config:addSubMenu("Auto Ult", "autoUlt")
	

	config.autoHeal:addParam("enabled", "Enable", SCRIPT_PARAM_ONOFF, true)
	config.autoHeal:addParam("healMode", "Heal Mode", SCRIPT_PARAM_LIST, DEFAULT_HEAL_MODE, { "Normal", "Smart", "Threshold" })
	
	config.autoStarcall:addParam("enabled", "Enable", SCRIPT_PARAM_ONOFF, true)
	config.autoStarcall:addParam("starcallMode", "Starcall Mode", SCRIPT_PARAM_LIST, DEFAULT_STARCALL_MODE, { "Harass Only", "Farm/Push", "Both (hit any)", "Both (hit enemy and minions)" })
	
	config.autoInfuse:addParam("enabled", "Enable", SCRIPT_PARAM_ONOFF, true)
	config.autoInfuse:addParam("infuseMode", "Infuse Mode", SCRIPT_PARAM_LIST, DEFAULT_INFUSE_MODE, { "Normal", "Smart"})
	
	config.autoUlt:addParam("enabled", "Enable", SCRIPT_PARAM_ONOFF, true)
	config.autoUlt:addParam("ultMode", "Ultimate Mode", SCRIPT_PARAM_LIST, DEFAULT_ULT_MODE, { "Selfish", "Lane Partner", "Entire Team" })
	
	-- Setup minion manager for enemy minions
	enemyMinions = minionManager(MINION_ENEMY, starcallRange, player, MINION_SORT_HEALTH_ASC)
end