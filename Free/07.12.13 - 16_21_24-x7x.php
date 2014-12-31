<?php exit() ?>--by x7x 89.70.161.4
if myHero.charName ~= "Katarina" then return end
---------------------------------- START UPDATE ---------------------------------------------------------------
local versionGOE = 0.9 -- current version
local SCRIPT_NAME_GOE = "aKatarina"
---------------------------------------------------------------------------------------------------------------
local needUpdate_GOE = false
local needRun_GOE = true
local URL_GOE = "http://anonymous-dev.y0.pl/aKatarina.lua" --"http://dlr5668.cuccfree.org/"..SCRIPT_NAME_GOE..".lua"
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
		if _G.UseUpdater == nil or _G.UseUpdater == true then GetAsyncWebResult("anonymous-dev.y0.pl", "aKatarinaVer.lua", CheckVersionGOE) end
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

local lastAnimation = "Run"
local ultActive = false
local dontMoveUntil = 0
local NextComboTick = 0
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,700,DAMAGE_MAGICAL,false)
local enemyMinions = minionManager(MINION_ENEMY, 2000, myHero, MINION_SORT_HEALTH_ASC)
local allyMinions = minionManager(MINION_ALLY, 2000, myHero, MINION_SORT_HEALTH_ASC)
local Enemies = {}
local CastR = false
local CastRtick = 0
local JumpToObject = nil
local waittxt = {}

function OnLoad()
	Menu = scriptConfig("aKatarina v"..versionGOE, "akatarina")
	ts.name = "aKatarina"
	Menu:addTS(ts)
	Menu:addSubMenu("General information", "InfoMenu")
	Menu.InfoMenu:addParam("Information1", "aKatarina v"..versionGOE.." created by Anonymous.", SCRIPT_PARAM_INFO, "")
	Menu.InfoMenu:addParam("Information2", "Enjoy and report bugs on forums!", SCRIPT_PARAM_INFO, "")
	Menu:addSubMenu("Combo settings [SBTW]", "ComboHelperMenu")
	Menu.ComboHelperMenu:addParam("ComboHelper","Combo key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu.ComboHelperMenu:addParam("useQ","Use Q automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("useW","Use W automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("useE","Use E automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("useR","Use R automaticly", SCRIPT_PARAM_ONOFF, true)
	Menu.ComboHelperMenu:addParam("UseMEC","Use MEC for ultimate", SCRIPT_PARAM_ONOFF, true)
	--Menu.ComboHelperMenu:addParam("useR","Use R automaticly", SCRIPT_PARAM_ONOFF, true)
	--[[Menu:addSubMenu("LastHit mode settings", "FarmHelperMenu")
	Menu.FarmHelperMenu:addParam("enabled","Lasthitting helper key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
	Menu.FarmHelperMenu:addParam("useQ","Use Q to kill minions", SCRIPT_PARAM_ONOFF, true)
	Menu.FarmHelperMenu:addParam("minMana","Minimum % mana to use Q", SCRIPT_PARAM_SLICE, 35, 0, 100, 0)
	Menu:addSubMenu("LaneClear mode settings", "ClearHelperMenu")
	Menu.ClearHelperMenu:addParam("enabled","Laneclear helper key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
	Menu.ClearHelperMenu:addParam("useQ","Use Q to kill minions", SCRIPT_PARAM_ONOFF, true)
	Menu.ClearHelperMenu:addParam("minMana","Minimum % mana to use Q", SCRIPT_PARAM_SLICE, 35, 0, 100, 0)]]
	--Menu.ClearHelperMenu:addParam("useR","Use R to kill minions", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("Drawer settings", "DrawHelperMenu")
	Menu.DrawHelperMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawWrange","Draw W range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawErange","Draw E range", SCRIPT_PARAM_ONOFF, true)
	Menu.DrawHelperMenu:addParam("drawRrange","Draw R range", SCRIPT_PARAM_ONOFF, false)
	Menu.DrawHelperMenu:addParam("drawHPtext","Draw damage output info", SCRIPT_PARAM_ONOFF, true)
	Menu:addSubMenu("Other settings", "OtherMenu")
	Menu.OtherMenu:addParam("useKSfunc","Automaticly secure kills", SCRIPT_PARAM_ONOFF, true)
	-- add TS
	local ordertxt = 1
	for i=1, heroManager.iCount do
		waittxt[i] = ordertxt
		ordertxt = ordertxt+1
		local EnemyHero = heroManager:getHero(i)
		if EnemyHero ~= nil and EnemyHero.team == TEAM_ENEMY then table.insert(Enemies, EnemyHero) end
	end
	PrintChat("<font color='#00BFFF'>aKatarina "..versionGOE.." loaded!</font>")
end

function OnTick()
	ts:update()
	enemyMinions:update()
	allyMinions:update()
	IsStillUlting()
	if CastR == true then CastRfunc() end
	local CanQ = (myHero:CanUseSpell(_Q) == READY)
	local CanW = (myHero:CanUseSpell(_W) == READY)
	local CanE = (myHero:CanUseSpell(_E) == READY)
	local CanR = (myHero:CanUseSpell(_R) == READY)
	if ultActive == true and CountEnemyHeroInRange(550) == 0 then ultActive = false end
	if Menu.ComboHelperMenu.ComboHelper and NextComboTick < GetTickCount() and not ultActive then
		if ts.target ~= nil then
			local Qdmg = 0
			local Wdmg = 0
			local Edmg = 0
			if CanQ then Qdmg = getDmg("Q", ts.target, myHero) end
			if CanW then Wdmg = getDmg("W", ts.target, myHero) end
			if CanE then Edmg = getDmg("E", ts.target, myHero) end
			if ts.target.health < Qdmg and GetDistance(ts.target) < 675 and CanQ then
				if Menu.ComboHelperMenu.useQ then CastSpell(_Q, ts.target) end
			elseif ts.target.health < Qdmg+Wdmg and CanQ and CanW and GetDistance(ts.target) < 375 then
				if Menu.ComboHelperMenu.useQ then CastSpell(_Q, ts.target) end
				if Menu.ComboHelperMenu.useW then CastSpell(_W) end
			elseif ts.target.health < Qdmg+Edmg and CanQ and CanE then
				if Menu.ComboHelperMenu.useE then CastSpell(_E, ts.target) end
				if Menu.ComboHelperMenu.useQ then CastSpell(_Q, ts.target) end
			elseif ts.target.health < Qdmg+Wdmg+Edmg and CanQ and CanW and CanE then
				if Menu.ComboHelperMenu.useE then CastSpell(_E, ts.target) end
				if Menu.ComboHelperMenu.useQ then CastSpell(_Q, ts.target) end
				if Menu.ComboHelperMenu.useW then CastSpell(_W) end
			else
				if CanE and CanR then
					-- MEC will do work.
				else
					if CanE and GetDistance(ts.target) < 700 then if Menu.ComboHelperMenu.useE then CastSpell(_E, ts.target) end end
				end
				if GetInventoryItemIsCastable(3128) then CastItem(3128, ts.target) end
				if CanQ and GetDistance(ts.target) < 675 then if Menu.ComboHelperMenu.useQ then CastSpell(_Q, ts.target) end end
				if CanW and GetDistance(ts.target) < 375 then if Menu.ComboHelperMenu.useW then CastSpell(_W) end end
				if CanR and GetDistance(ts.target) < 550 then
					CastR = true
					CastRtick = GetTickCount() + 20
					NextComboTick = GetTickCount() + 70
				end
			end
		end
	end
	if Menu.OtherMenu.useKSfunc and NextComboTick < GetTickCount() and not ultActive then
		for _, Target in pairs(Enemies) do
			if Target ~= nil and Target.valid and not Target.dead and Target.team == TEAM_ENEMY and GetDistance(Target) < 2000 then
				local Qdmg = 0
				local Wdmg = 0
				local Edmg = 0
				if CanQ then Qdmg = getDmg("Q", Target, myHero) end
				if CanW then Wdmg = getDmg("W", Target, myHero) end
				if CanE then Edmg = getDmg("E", Target, myHero) end
				if CanW and Wdmg > Target.health and GetDistance(Target) < 375 then
					CastSpell(_W)
				elseif CanQ and Qdmg > Target.health and GetDistance(Target) < 675 then
					CastSpell(_Q, Target)
				elseif CanE and Edmg > Target.health and GetDistance(Target) < 700 then
					CastSpell(_E, Target)
				elseif CanQ and CanW and Qdmg+Wdmg > Target.health and GetDistance(Target) < 375 then
					CastSpell(_Q, Target)
					CastSpell(_W)
				elseif CanE and CanW and Wdmg+Edmg > Target.health and GetDistance(Target) < 700 then
					CastSpell(_E, Target)
					CastSpell(_W)
				elseif CanE and CanQ and Qdmg+Edmg > Target.health and GetDistance(Target) < 700 then
					CastSpell(_E, Target)
					CastSpell(_Q, Target)
				elseif CanQ and CanW and CanE and Qdmg+Wdmg+Edmg > Target.health and GetDistance(Target) < 700 then
					CastSpell(_E, Target)
					CastSpell(_Q, Target)
					CastSpell(_W)
				end
			end
		end
	end
end

function WardJump(x, y)
	if myHero:CanUseSpell(_E) == READY then
		if JumpToObject ~= nil then
		
		else
			local JumpSlot
			local rstSlot, ssSlot, swSlot, vwSlot = GetInventorySlotItem(2045), GetInventorySlotItem(2049), GetInventorySlotItem(2044), GetInventorySlotItem(2043)
			local RSTREADY = (rstSlot ~= nil and myHero:CanUseSpell(rstSlot) == READY)
			local SSREADY = (ssSlot ~= nil and myHero:CanUseSpell(ssSlot) == READY)
			local SWREADY = (swSlot ~= nil and myHero:CanUseSpell(swSlot) == READY)
			local VWREADY = (vwSlot ~= nil and myHero:CanUseSpell(vwSlot) == READY)
		end
	end
end

function CastRfunc()
	local CanE = (myHero:CanUseSpell(_E) == READY)
	local CanR = (myHero:CanUseSpell(_R) == READY)
	if CastR then
		if CanR and GetTickCount() > CastRtick then
			ultActive = true
			CastR = false
			if Menu.ComboHelperMenu.UseMEC and CanE and CountEnemyHeroInRange(1200) > 1 then
				local spellPos = GetAoESpellPosition(550, ts.target)
				local ClosestToMEC = ts.target
				for _, target in pairs(enemyMinions.objects) do
					if target.valid and not target.dead and GetDistance(target, spellPos) < GetDistance(ts.target, spellPos) and GetDistance(target) < 700 then
						ClosestToMEC = target
					end
				end
				for _, target in pairs(allyMinions.objects) do
					if target.valid and not target.dead and GetDistance(target, spellPos) < GetDistance(ts.target, spellPos) and GetDistance(target) < 700 then
						ClosestToMEC = target
					end
				end
				for _, target in pairs(Enemies) do
					if target.valid and not target.dead and GetDistance(target, spellPos) < GetDistance(ts.target, spellPos) and GetDistance(target) < 700 then
						ClosestToMEC = target
					end
				end
				CastSpell(_E, ClosestToMEC)
				if GetDistance(ts.target) < 550 then if Menu.ComboHelperMenu.useR then CastSpell(_R) end end
			else
				if GetDistance(ts.target) < 550 then if Menu.ComboHelperMenu.useR then CastSpell(_R) end end
			end
		end
	end
end

function OnDraw()
	if Menu.DrawHelperMenu.drawQrange then DrawCircle(myHero.x, myHero.y, myHero.z, 675, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawWrange then DrawCircle(myHero.x, myHero.y, myHero.z, 375, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawErange then DrawCircle(myHero.x, myHero.y, myHero.z, 700, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawRrange then DrawCircle(myHero.x, myHero.y, myHero.z, 550, RGB(0,191,255)) end
	if Menu.DrawHelperMenu.drawHPtext then
		local CanQ = (myHero:CanUseSpell(_Q) == READY)
		local CanW = (myHero:CanUseSpell(_W) == READY)
		local CanE = (myHero:CanUseSpell(_E) == READY)
		local CanR = (myHero:CanUseSpell(_R) == READY)
		local CanDFG = GetInventoryItemIsCastable(3128)
		for n, DrawTarget in pairs(Enemies) do
			if DrawTarget ~= nil and DrawTarget.valid and not DrawTarget.dead and DrawTarget.team == TEAM_ENEMY then
				local DFGdmg = 0
				local TotalDMG = 0
				local Qdmg = getDmg("Q", DrawTarget, myHero)
				local Wdmg = getDmg("W", DrawTarget, myHero)
				local Edmg = getDmg("E", DrawTarget, myHero)
				local Rdmg = getDmg("R", DrawTarget, myHero)*10
				if CanDFG then
					TotalDMG = (Qdmg+Wdmg+Edmg+Rdmg)*1.2
					TotalDMG = TotalDMG + getDmg("DFG", DrawTarget, myHero)
				else
					TotalDMG = Qdmg+Wdmg+Edmg+Rdmg
				end
				if Qdmg+Wdmg+Edmg > DrawTarget.health then
					if waittxt[n] == 1 then 
						PrintFloatText(DrawTarget, 0, "MURDER HIM")
						waittxt[n] = 10
					else
						waittxt[n] = waittxt[n]-1
					end
					for j=0, 5 do
						--DrawCircle(DrawTarget.x, DrawTarget.y, DrawTarget.z, 40 + j*2, RGB(255,0,0))
					end
				elseif TotalDMG > DrawTarget.health then
					if waittxt[n] == 1 then 
						PrintFloatText(DrawTarget, 0, "Killable")
						waittxt[n] = 10
					else
						waittxt[n] = waittxt[n]-1
					end
					for j=0, 5 do
						--DrawCircle(DrawTarget.x, DrawTarget.y, DrawTarget.z, 40 + j*2, RGB(255,255,0))
					end
				else
					if waittxt[n] == 1 then 
						PrintFloatText(DrawTarget, 0, "Not killable")
						waittxt[n] = 10
					else
						waittxt[n] = waittxt[n]-1
					end
					for j=0, 5 do
						--DrawCircle(DrawTarget.x, DrawTarget.y, DrawTarget.z, 40 + j*2, RGB(0,255,0))
					end
				end
			end
		end	
	end
end

function OnProcessSpell(me, spell) -- lel me.isMe :P
	if me.isMe and spell.name == "KatarinaR" then
		ultActive = true
		dontMoveUntil = GetTickCount() + 100
	end
	--if me.isMe then PrintChat(spell.name) end
end

function OnAnimation(me, anim)
	if me.isMe and anim ~= lastAnimation then lastAnimation = anim end
end

function IsStillUlting()
	if ultActive then
		if lastAnimation ~= "Spell4" then
			ultActive = false
		end
	else
		ultActive = false
	end
end

function OnUnload()
	PrintChat("<font color='#00BFFF'>aKatarina "..versionGOE.." unloaded :(!</font>")
end

function OnSendPacket(packet)
	local p = Packet(packet)
end

function CastTrinket(xPos, yPos)
	local p = CLoLPacket(0x9A)
	p.dwArg1 = 1
	p.dwArg2 = 0
	p:EncodeF(myHero.networkID)
	p:Encode1(10)
	p:EncodeF(mousePos.x)
	p:EncodeF(mousePos.z)
	p:EncodeF(xPos)--toX
	p:EncodeF(yPos)--toZ
	p:EncodeF(0)
	SendPacket(p)
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