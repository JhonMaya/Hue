<?php exit() ?>--by x7x 89.70.161.4
if myHero.charName ~= "Orianna" then return end
---------------------------------- START UPDATE ---------------------------------------------------------------
local versionGOE = 1.02 -- current version
local SCRIPT_NAME_GOE = "aOrianna"
---------------------------------------------------------------------------------------------------------------
local needUpdate_GOE = false
local needRun_GOE = true
local URL_GOE = "http://anonymous-dev.y0.pl/"..SCRIPT_NAME_GOE..".lua" --"http://dlr5668.cuccfree.org/"..SCRIPT_NAME_GOE..".lua"
local PATH_GOE = BOL_PATH.."Scripts\\"..SCRIPT_NAME_GOE..".lua"
function CheckVersionGOE(data)
	local onlineVerGOE = tonumber(data)
	if type(onlineVerGOE) ~= "number" then return end
	if onlineVerGOE and onlineVerGOE > versionGOE then
		print("<font color='#00BFFF'>AUTOUPDATER: There is a new version of "..SCRIPT_NAME_GOE..". Don't F9 till done...</font>") 
		needUpdate_GOE = true  
	end
end
function UpdateScriptGOE()
	if needRun_GOE then
		needRun_GOE = false
		if _G.UseUpdater == nil or _G.UseUpdater == true then GetAsyncWebResult("anonymous-dev.y0.pl", SCRIPT_NAME_GOE.."Ver.lua", CheckVersionGOE) end
	end

	if needUpdate_GOE then
		needUpdate_GOE = false
		DownloadFile(URL_GOE, PATH_GOE, function()
                if FileExist(PATH_GOE) then
                    print("<font color='#00BFFF'>AUTOUPDATER: Script updated! Reload scripts to use new version!</font>")
                end
            end)
	end
end
AddTickCallback(UpdateScriptGOE)
---------------------------------- END UPDATE -----------------------------------------------------------------

-- aKatarina by Anonymous BETA --
-- color: #00BFFF
-- RGB(0,191,255)

--[[
	TODOLIST
	- [X] QWE COMBO
	- [X] R unbreakable.
	- [ ] Wardjump + Trinketjump ( PACKET )
	- [X] Drawing shit on screen. ( crcles, ranges)
	- [ ] Drawing killable, and text
	- [X] KS combo
	
	lowpriority
	- [ ] WardJump->KS if enemy killable out of Q range.
]]

local Prodiction = nil
local QProdict
if VIP_USER then
	require "Prodiction"
	Prodiction = ProdictManager.GetInstance()
end
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,700,DAMAGE_MAGICAL,false)
local enemyMinions = minionManager(MINION_ENEMY, 2000, myHero, MINION_SORT_HEALTH_ASC)
local allyMinions = minionManager(MINION_ALLY, 2000, myHero, MINION_SORT_HEALTH_ASC)
local Enemies = {}
local waittxt = {}
local CanQ = false
local CanW = false
local CanE = false
local CanR = false
local Ball = myHero
local BallMoving = false
local LastQ = 0

function CastQ(unit)
	local pos = QProdict:GetPrediction(unit, myHero)
	if GetDistance(pos) < 825 then CastSpell(_Q, pos.x, pos.z) end
end

function OnLoad()
	if VIP_USER and Prodiction ~= nil then QProdict = Prodiction:AddProdictionObject(_Q, 825, 1200, 0.25, 80, myHero) else QProdict = TargetPrediction(825, 1.2, 250, 80, 0) end
	Menu = scriptConfig("aOrianna v"..versionGOE, "aorianna")
	ts.name = "aOrianna"
	Menu:addTS(ts)
	Menu:addSubMenu("General information", "InfoMenu")
	Menu.InfoMenu:addParam("Information1", "aOrianna v"..versionGOE.." created by Anonymous.", SCRIPT_PARAM_INFO, "")
	Menu.InfoMenu:addParam("Information2", "Enjoy and report bugs on forums!", SCRIPT_PARAM_INFO, "")
	Menu:addSubMenu("Combo settings [SBTW]", "ComboHelperMenu")
	Menu.ComboHelperMenu:addParam("ComboHelper","Combo key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu.ComboHelperMenu:addParam("useQ","Use Q in combo", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("useW","Use W in combo", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("useE","Use E in combo", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("useR","Use R in combo if:", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("Renemies","- can catch X enemies:", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
	Menu.ComboHelperMenu:addParam("useRtoks","- kill single enemy", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("UseMEC","Use MEC for combo (BETA)", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("Information1", "If MEC is enabled then it will use Q", SCRIPT_PARAM_INFO, "")
	Menu.ComboHelperMenu:addParam("Information2", "to get ball in best position to hit as", SCRIPT_PARAM_INFO, "")
	Menu.ComboHelperMenu:addParam("Information3", "much as possible enemies at once with R.", SCRIPT_PARAM_INFO, "")
	Menu.ComboHelperMenu:addParam("Information4", "It will trigger only if nearby enemies >= 2", SCRIPT_PARAM_INFO, "")
	Menu:addSubMenu("Harras settings [AUTO]", "Harras")
	Menu.Harras:addParam("AutoHarras","Automaticly harars enemies", SCRIPT_PARAM_ONOFF, true)
	Menu.Harras:addParam("MinMana","if mana is over X%", SCRIPT_PARAM_SLICE, 35, 0, 100, 0)
	Menu.Harras:addParam("useQ","Use Q", SCRIPT_PARAM_ONOFF, true)
	Menu.Harras:addParam("useW","Use W", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("Drawer settings", "DrawHelperMenu")
	Menu.DrawHelperMenu:addParam("drawReturnRange","Draw ball return range", SCRIPT_PARAM_ONOFF, true)
	Menu.DrawHelperMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, true)
	Menu.DrawHelperMenu:addParam("drawWrange","Draw W range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawErange","Draw E range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawRrange","Draw R range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawHPtext","Draw damage output info", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("Other settings", "OtherMenu")
	Menu.OtherMenu:addParam("autoUlt","Automaticly ultimate", SCRIPT_PARAM_ONOFF, true)
	Menu.OtherMenu:addParam("autoMinEnemies","if can catch X enemies:", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	Menu.OtherMenu:addParam("KillUlt","Automaticly kill with ultimate", SCRIPT_PARAM_ONOFF, true)
	Menu.OtherMenu:addParam("KillMinEnemies","if can kill X enemies:", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
	local ordertxt = 1
	for i=1, heroManager.iCount do
		waittxt[i] = ordertxt
		ordertxt = ordertxt+1
		local EnemyHero = heroManager:getHero(i)
		if EnemyHero ~= nil and EnemyHero.team == TEAM_ENEMY then table.insert(Enemies, EnemyHero) end
	end
	PrintChat("<font color='#00BFFF'>aOrianna "..versionGOE.." loaded!</font>")
end

function OnTick()
	ts:update()
	enemyMinions:update()
	allyMinions:update()
	CanQ = (myHero:CanUseSpell(_Q) == READY)
	CanW = (myHero:CanUseSpell(_W) == READY)
	CanE = (myHero:CanUseSpell(_E) == READY)
	CanR = (myHero:CanUseSpell(_R) == READY)
	if ts.target ~= nil and not BallMoving then
		if Menu.ComboHelperMenu.ComboHelper then
			if CountEnemyHeroInRange(1500) >= 2 and Menu.ComboHelperMenu.UseMEC then
				if Menu.ComboHelperMenu.useQ and CanQ then
					if CanR then
						local spellPos = GetAoESpellPosition(400, ts.target)
						CastSpell(_Q, spellPos.x, spellPos.z)
					else
						CastQ(ts.target)
					end
				end
				if Menu.ComboHelperMenu.useW and CanW then
					if GetDistance(Ball, ts.target) < 250 then CastSpell(_W) end
				end
				if Menu.ComboHelperMenu.useE and CanE and Ball ~= nil and Ball ~= myHero and not CanR then
					local CanHit = checkhitlinepass(Ball, myHero, 35, 1100, Target, GetDistance(ts.target, ts.target.minBBox)/2)
					if CanHit then CastSpell(_E, myHero) end
				end
				if Menu.ComboHelperMenu.useR and CanR and not CanQ then
					local ValidTargets = 0
					for i, Enemy in pairs(Enemies) do
						if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 then ValidTargets = ValidTargets + 1 end
					end
					if ValidTargets >= Menu.ComboHelperMenu.Renemies then CastSpell(_R) end	
				end
			else
				if Menu.ComboHelperMenu.useQ and CanQ then CastQ(ts.target) end
				if Menu.ComboHelperMenu.useW and CanW then
					if GetDistance(Ball, ts.target) < 250 then CastSpell(_W) end
				end
				if Menu.ComboHelperMenu.useE and CanE and Ball ~= nil and Ball ~= myHero then
					local CanHit = checkhitlinepass(Ball, myHero, 35, 1100, Target, GetDistance(ts.target, ts.target.minBBox)/2)
					if CanHit then CastSpell(_E, myHero) end
				end
				if Menu.ComboHelperMenu.useR and Menu.ComboHelperMenu.useRtoks and CanR then
					if ts.target.health < getDmg("R", ts.target, myHero) then CastSpell(_R) end
				end
			end
		end
		if Menu.Harras.AutoHarras then
			if myHero.mana/myHero.maxMana*100 >= Menu.Harras.MinMana then 
				if Menu.Harras.useQ and CanQ then
					CastQ(ts.target)
				end
				if Menu.Harras.useW and CanW then
					if GetDistance(Ball, ts.target) < 250 then CastSpell(_W) end
				end
			end
		end
	end
	if Menu.OtherMenu.autoUlt then
		local ValidTargets = 0
		for i, Enemy in pairs(Enemies) do
			if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 then ValidTargets = ValidTargets + 1 end
		end
		if ValidTargets >= Menu.OtherMenu.autoMinEnemies then CastSpell(_R) end
	end
	if Menu.OtherMenu.KillUlt then
		local KillableTargets = 0
		for i, Enemy in pairs(Enemies) do
			if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 and getDmg("R", Enemy, myHero) >= Enemy.health then KillableTargets = KillableTargets + 1 end
		end
		if KillableTargets >= Menu.OtherMenu.KillMinEnemies then CastSpell(_R) end
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe and spell.name == "OrianaRedactCommand" then
		Ball = spell.target
		BallMoving = false
	end
	if unit.isMe and spell.name == "OrianaIzunaCommand" then
		BallMoving = true
		LastQ = GetTickCount()
	end
end

function OnCreateObj(obj)
	if obj == nil or obj.name == nil then return end
	if (obj.name:find("yomu_ring_green")) then
		Ball = obj
		BallMoving = false
	end
	if (obj.name:find("Orianna_Ball_Flash_Reverse")) then
		Ball = myHero
		BallMoving = false
	end
end

function OnDraw()
	if Ball ~= nil and not Ball.isMe and Menu.DrawHelperMenu.drawReturnRange then DrawCircle(Ball.x, Ball.y, Ball.z, 1325, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawQrange then DrawCircle(myHero.x, myHero.y, myHero.z, 825, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawWrange and Ball ~= myHero then DrawCircle(Ball.x, Ball.y, Ball.z, 250, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawErange then DrawCircle(myHero.x, myHero.y, myHero.z, 1100, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawRrange and Ball ~= myHero then DrawCircle(Ball.x, Ball.y, Ball.z, 400, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawHPtext then
		local CanDFG = GetInventoryItemIsCastable(3128)
		for n, DrawTarget in pairs(Enemies) do
			if DrawTarget ~= nil and DrawTarget.valid and not DrawTarget.dead and DrawTarget.team == TEAM_ENEMY then
				local DFGdmg = 0
				local TotalDMG = 0
				local Qdmg = getDmg("Q", DrawTarget, myHero)
				local Wdmg = getDmg("W", DrawTarget, myHero)
				local Rdmg = 0
				if CanR then
					Rdmg = getDmg("R", DrawTarget, myHero)
				end
				if CanDFG then
					TotalDMG = (Qdmg+Wdmg+Rdmg)*1.2
					TotalDMG = TotalDMG + getDmg("DFG", DrawTarget, myHero)
				else
					TotalDMG = Qdmg+Wdmg+Rdmg
				end
				if Qdmg+Wdmg > DrawTarget.health then
					if waittxt[n] == 1 then 
						PrintFloatText(DrawTarget, 0, "MURDER HIM")
						waittxt[n] = 10
					else
						waittxt[n] = waittxt[n]-1
					end
				elseif TotalDMG > DrawTarget.health then
					if waittxt[n] == 1 then 
						PrintFloatText(DrawTarget, 0, "Killable")
						waittxt[n] = 10
					else
						waittxt[n] = waittxt[n]-1
					end
				else
					if waittxt[n] == 1 then 
						PrintFloatText(DrawTarget, 0, "Not killable")
						waittxt[n] = 10
					else
						waittxt[n] = waittxt[n]-1
					end
				end
			end
		end	
	end
end

function OnUnload()
	PrintChat("<font color='#00BFFF'>aOrianna "..versionGOE.." unloaded :(!</font>")
end

function OnSendPacket(p)
	local packet = Packet(p)
	if packet:get('name') == 'S_CAST' then
		local SpellID = packet:get('spellId')
		if SpellID == SPELL_4 then
			local ValidTargets = 0
			for i, Enemy in pairs(Enemies) do
				if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 then ValidTargets = ValidTargets + 1 end
			end
			if ValidTargets == 0 then p:Block() end
		end
	end
end

--[[
        AoESkillshotPosition 2.0 by monogato
       
        GetAoESpellPosition(radius, main_target, [delay]) returns best position in order to catch as many enemies as possible with your AoE skillshot, making sure you get the main target.
        Note: You can optionally add delay in ms for prediction (VIP if avaliable, normal else).
]]
 
function GetCenter(points)
        local sum_x = 0
        local sum_z = 0
       
        for i = 1, #points do
                sum_x = sum_x + points[i].x
                sum_z = sum_z + points[i].z
        end
       
        local center = {x = sum_x / #points, y = 0, z = sum_z / #points}
       
        return center
end
 
function ContainsThemAll(circle, points)
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
function FarthestFromPositionIndex(points, position)
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
 
function RemoveWorst(targets, position)
        local worst_target = FarthestFromPositionIndex(targets, position)
       
        table.remove(targets, worst_target)
       
        return targets
end
 
function GetInitialTargets(radius, main_target)
        local targets = {main_target}
        local diameter_sqr = 4 * radius * radius
       
        for i=1, heroManager.iCount do
                target = heroManager:GetHero(i)
                if target.networkID ~= main_target.networkID and ValidTarget(target) and GetDistanceSqr(main_target, target) < diameter_sqr then table.insert(targets, target) end
        end
       
        return targets
end
 
function GetPredictedInitialTargets(radius, main_target, delay)
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