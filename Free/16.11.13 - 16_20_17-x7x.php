<?php exit() ?>--by x7x 89.70.178.198
--
--              aEvelynn by Anonymous v1.2
--				Exploits + Laught after kill + Ignite in full combo :D
--

if myHero.charName ~= "Evelynn" then return end

local Target
local UltByScript = false
local Enemies = AutoCarry.EnemyTable
local iSlot = nil

function PluginOnLoad()
	AutoCarry.SkillsCrosshair.range = 800
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		iSlot = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		iSlot = SUMMONER_2
	else 
		iSlot = nil
	end
end

function PluginOnTick()
	Target = AutoCarry.GetAttackTarget()
	if AutoCarry.PluginMenu.spamQE then
		if Target ~= nil and AutoCarry.MainMenu.AutoCarry then
			local CanQ = (myHero:CanUseSpell(_Q) == READY and GetDistance(Target, myHero) < 500)
			local CanE = (myHero:CanUseSpell(_E) == READY and GetDistance(Target, myHero) < 225)
			if CanQ then CastSpell(_Q) end
			if CanE then castE(Target) end
		end
	end
	if AutoCarry.PluginMenu.spamQ then
		if AutoCarry.MainMenu.LaneClear and myHero.mana/myHero.maxMana*100 >= AutoCarry.PluginMenu.autoMinMana then
			local CanQ = (myHero:CanUseSpell(_Q) == READY)
			if CanQ then CastSpell(_Q) end
		end
	end
	if AutoCarry.PluginMenu.spamE then
		if AutoCarry.MainMenu.LaneClear and myHero.mana/myHero.maxMana*100 >= AutoCarry.PluginMenu.autoMinMana then
			local JunngleTarget = AutoCarry.GetMinionTarget()
			if JunngleTarget ~= nil then
				local CanE = (myHero:CanUseSpell(_E) == READY and GetDistance(JunngleTarget, myHero) < 225)
				if CanE then castE(JunngleTarget) end
			end
		end
	end
	if AutoCarry.PluginMenu.burstTarget then
		if Target ~= nil then
			local CanQ = (myHero:CanUseSpell(_Q) == READY and GetDistance(Target, myHero) < 500)
			local CanE = (myHero:CanUseSpell(_E) == READY and GetDistance(Target, myHero) < 325)
			local CanR = (myHero:CanUseSpell(_R) == READY and GetDistance(Target, myHero) < 800)
			AutoCarry.Items:UseAll(Target)
			CastItem(3128, Target)
			if CanR then
				if VIP_USER then
					if AutoCarry.PluginMenu.autoUltaiming then 
						CastSpell(_R, Target.x, Target.z)
					else
						local spellPos = GetAoESpellPosition(250, Target)
						CastSpell(_R, spellPos.x, spellPos.z)
					end
				else
					local spellPos = GetAoESpellPosition(250, Target)
					CastSpell(_R, spellPos.x, spellPos.z)
					--PrintChat("NonVIPCast")
				end
			end -- Packets will target it better anyway :p
			if CanE then castE(Target) end
			if CanQ then CastSpell(_Q) end
			if useAA then if not AutoCarry.MainMenu.AutoCarry then myHero:Attack(Target) end end
		end
	end
	if AutoCarry.PluginMenu.useIGNITE and iSlot ~= nil and Target ~= nil then
		local CanIgnite = (iSlot ~= nil and myHero:CanUseSpell(iSlot) == READY)
		if CanIgnite then
			if Target.health < getDmg("IGNITE", Target, myHero) then
				CastSpell(iSlot, Target)
			end
		end
	end
end

function PluginOnDraw()
	if myHero ~= nil and not myHero.dead and AutoCarry.PluginMenu.drawQrange then DrawCircle(myHero.x, myHero.y, myHero.z, 500, 0xFF80FF00) end
	if myHero ~= nil and not myHero.dead and AutoCarry.PluginMenu.drawErange then DrawCircle(myHero.x, myHero.y, myHero.z, 225, 0xFF80FF00) end
	if myHero ~= nil and not myHero.dead and AutoCarry.PluginMenu.drawRrange and myHero:CanUseSpell(_R) == READY then DrawCircle(myHero.x, myHero.y, myHero.z, 800, 0xFF80FF00) end
	if AutoCarry.PluginMenu.drawRmec then -- Test MEC IT Draws where ultimate will be casted :) You can uncomment it, and it will work.
		local ClosestEnemy = nil
		local Position1 = { x=mousePos.x, y=mousePos.y, z=mousePos.z }
		for i, Enemy in pairs(Enemies) do
			if Enemy ~= nil and not Enemy.dead and Enemy.visible then
				if ClosestEnemy ~= nil then
					if GetDistance(Enemy, Position1) < GetDistance(ClosestEnemy, Position1) then
						ClosestEnemy = Enemy
					end
				else
					ClosestEnemy = Enemy
				end
			end
		end
		local UltTargetted = ClosestEnemy
		if UltTargetted ~= nil and GetDistance(UltTargetted, CastPosition) < 300 then
			UltTarget = UltTargetted
		else
			UltTarget = AutoCarry.GetAttackTarget()
		end
		if UltTarget ~= nil then
			local ValidEnemies = 0
			for i, Enemy in pairs(Enemies) do
				if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Enemy, myHero) < 1050 then ValidEnemies = ValidEnemies + 1 end
			end
			if ValidEnemies > 1 then 
				local spellPos = GetAoESpellPosition(250, UltTarget)
				DrawCircle(spellPos.x, spellPos.y, spellPos.z, 250, 0xFF80FF00)
			else
				DrawCircle(UltTarget.x, UltTarget.y, UltTarget.z, 250, 0xFF80FF00)
			end
		end
	end
	if myHero ~= nil and not myHero.dead and AutoCarry.PluginMenu.drawText then
		for i, Enemy in pairs(Enemies) do
			if ValidTarget(Enemy) then
				local TotalDMG = 0
				local CanDFG = GetInventoryItemIsCastable(3128)
				local CanQ = (myHero:CanUseSpell(_Q) == READY)
				local CanE = (myHero:CanUseSpell(_E) == READY)
				local CanR = (myHero:CanUseSpell(_R) == READY)
				local CanIgnite = (iSlot ~= nil and myHero:CanUseSpell(iSlot) == READY)
				if CanR then TotalDMG = TotalDMG + getDmg("R", Enemy, myHero) end
				if CanE then TotalDMG = TotalDMG + getDmg("E", Enemy, myHero) end
				if CanQ then TotalDMG = TotalDMG + getDmg("Q", Enemy, myHero) end
				if CanDFG then
					TotalDMG = TotalDMG * 1.2
					TotalDMG = TotalDMG + getDmg("DFG", Enemy, myHero)
				end
				TotalDMG = TotalDMG + getDmg("AD", Enemy, myHero)
				if CanIgnite then TotalDMG = TotalDMG + getDmg("IGNITE", Enemy, myHero) end
				if TotalDMG > Enemy.health then
					PrintFloatText(Enemy, 0, "Killable")
				else
					local HPafter = round(Enemy.health - TotalDMG, 1)
					PrintFloatText(Enemy, 0, "HP: "..HPafter)
				end
			end
		end
	end
end

function round(num, idp)
  return tonumber(string.format("%." .. (idp or 0) .. "f", num))
end

function castE(target)
	if target.valid then
		if VIP_USER and AutoCarry.PluginMenu.useExploit then
			Packet('S_CAST', {fromX = mousePos.x, fromY = mousePos.z, targetNetworkId = target.networkID, spellId = SPELL_3}):send()
		else
			CastSpell(_E, target)
		end
	end
end

function PluginOnSendPacket(p)
	local packet = Packet(p)
	if packet:get('name') == 'S_CAST' then
		local SpellID = packet:get('spellId')
		if SpellID == SPELL_4 then
			local CastPosition = { x = packet:get('toX'), y = packet:get('toY'), z = packet:get('toY') }
			if AutoCarry.PluginMenu.blockR then
				local ValidTargets = 0
				for i, Enemy in pairs(Enemies) do
					if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Enemy, CastPosition) < 250 then ValidTargets = ValidTargets + 1 end
				end
				if ValidTargets == 0 then p:Block() end
			end
			if AutoCarry.PluginMenu.autoUltaiming then
				if UltByScript == true then
					UltByScript = false
				elseif UltByScript == false then
					p:Block()
					UltByScript = true
					local ClosestEnemy = nil
					local Position1 = { x=CastPosition.x, y=CastPosition.y, z=CastPosition.z }
					for i, Enemy in pairs(Enemies) do
						if Enemy ~= nil and not Enemy.dead and Enemy.visible then
							if ClosestEnemy ~= nil then
								if GetDistance(Enemy, Position1) < GetDistance(ClosestEnemy, Position1) then
									ClosestEnemy = Enemy
								end
							else
								ClosestEnemy = Enemy
							end
						end
					end
					local UltTargetted = ClosestEnemy
					if UltTargetted ~= nil and GetDistance(UltTargetted, CastPosition) < 300 then
						UltTarget = UltTargetted
					else
						UltTarget = AutoCarry.Crosshair:GetTarget()
					end
					if UltTarget ~= nil then
						local ValidEnemies = 0
						for i, Enemy in pairs(Enemies) do
							if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Enemy, myHero) < 1050 then ValidEnemies = ValidEnemies + 1 end
						end
						if ValidEnemies > 1 then 
							local spellPos = GetAoESpellPosition(250, UltTarget)
							CastSpell(_R, spellPos.x, spellPos.z)
						else
							CastSpell(_R, UltTarget.x, UltTarget.z)
						end
					end
				end
			end
		end
	end
end

--AutoCarry.PluginMenu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "aEvelynn v1.2")
AutoCarry.PluginMenu:addParam("Information1", "  aEvelynn v1.2 by Anonymous", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("Information2", "== Helper-Settings: ==", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("burstTarget", "Burst Combo", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
AutoCarry.PluginMenu:addParam("useAA","Autoattack in Burst Combo", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("useIGNITE","Use Ignite on killable enemies", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("spamQE","Use QE in AutoCarry mode", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("spamQ","Use Q in LaneClear mode", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("spamE","Use E in LaneClear mode", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("autoMinMana","Minimum % mana (LaneClear)", SCRIPT_PARAM_SLICE, 35, 0, 100, 0)
--Menu:addParam("spamQblueonly","Only with bluebuff", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("Information4", "VIP only functions:", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("useExploit","Use spell casting exploits", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("blockR","Block wrong ult", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("autoUltaiming","Automaticly aim with ult", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("Information3", "== Drawer-Settings: ==", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("drawErange","Draw E range", SCRIPT_PARAM_ONOFF, false) -- useless imo, too much circles.
AutoCarry.PluginMenu:addParam("drawRrange","Draw R range", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("drawRmec","Draw R prediction", SCRIPT_PARAM_ONOFF, false) -- This is only to let you see how it casts ultimate, imo should be disable during game.
AutoCarry.PluginMenu:addParam("drawText","Draw text on enemies", SCRIPT_PARAM_ONOFF, true) -- Killable, Murder him, etc.

--[[ 
	AoE_Skillshot_Position 2.0 by monogato
	
	GetAoESpellPosition(radius, main_target, [delay]) returns best position in order to catch as many enemies as possible with your AoE skillshot, making sure you get the main target.
	Note: You can optionally add delay in ms for prediction (VIP if avaliable, normal else).
]]

local function GetCenter(points)
	local sum_x = 0
	local sum_z = 0
	
	for i = 1, #points do
		sum_x = sum_x + points[i].x
		sum_z = sum_z + points[i].z
	end
	
	local center = {x = sum_x / #points, y = 0, z = sum_z / #points}
	
	return center
end

local function ContainsThemAll(circle, points)
	local radius_sqr = circle.radius*circle.radius
	local contains_them_all = true
	local i = 1
	
	while contains_them_all and i <= #points do
		contains_them_all = GetDistanceSqr(points[i], circle.center) <= radius_sqr
		i = i + 1
	end
	
	return contains_them_all
end

-- The first element (which is gonna be main_target) is untouchable.
local function FarthestFromPositionIndex(points, position)
	local index = 2
	local actual_dist_sqr
	local max_dist_sqr = GetDistanceSqr(points[index], position)
	
	for i = 3, #points do
		actual_dist_sqr = GetDistanceSqr(points[i], position)
		if actual_dist_sqr > max_dist_sqr then
			index = i
			max_dist_sqr = actual_dist_sqr
		end
	end
	
	return index
end

local function RemoveWorst(targets, position)
	local worst_target = FarthestFromPositionIndex(targets, position)
	
	table.remove(targets, worst_target)
	
	return targets
end

local function GetInitialTargets(radius, main_target)
	local targets = {main_target}
	local diameter_sqr = 4 * radius * radius
	
	for i=1, heroManager.iCount do
		target = heroManager:GetHero(i)
		if target.networkID ~= main_target.networkID and ValidTarget(target) and GetDistanceSqr(main_target, target) < diameter_sqr then table.insert(targets, target) end
	end
	
	return targets
end

local function GetPredictedInitialTargets(radius, main_target, delay)
	if VIP_USER and not vip_target_predictor then vip_target_predictor = TargetPredictionVIP(nil, nil, delay/1000) end
	local predicted_main_target = VIP_USER and vip_target_predictor:GetPrediction(main_target) or GetPredictionPos(main_target, delay)
	local predicted_targets = {predicted_main_target}
	local diameter_sqr = 4 * radius * radius
	
	for i=1, heroManager.iCount do
		target = heroManager:GetHero(i)
		if ValidTarget(target) then
			predicted_target = VIP_USER and vip_target_predictor:GetPrediction(target) or GetPredictionPos(target, delay)
			if target.networkID ~= main_target.networkID and GetDistanceSqr(predicted_main_target, predicted_target) < diameter_sqr then table.insert(predicted_targets, predicted_target) end
		end
	end
	
	return predicted_targets
end

-- I don´t need range since main_target is gonna be close enough. You can add it if you do.
function GetAoESpellPosition(radius, main_target, delay)
	local targets = delay and GetPredictedInitialTargets(radius, main_target, delay) or GetInitialTargets(radius, main_target)
	local position = GetCenter(targets)
	local best_pos_found = true
	local circle = Circle(position, radius)
	circle.center = position
	
	if #targets > 2 then best_pos_found = ContainsThemAll(circle, targets) end
	
	while not best_pos_found do
		targets = RemoveWorst(targets, position)
		position = GetCenter(targets)
		circle.center = position
		best_pos_found = ContainsThemAll(circle, targets)
	end
	
	return position
end