<?php exit() ?>--by 16hex16 105.236.253.215
local authed = false
local function abs(k) if k < 0 then return -k else return k end end
if tonumber == nil or tonumber("223") ~= 223 or -9 ~= "-10" + 1 then return end
if tostring == nil or tostring(220) ~= "220" then return end
if string.sub == nil or string.sub("imahacker", 4) ~= "hacker" then return end
last1 = tonumber(string.sub(tostring(GetUser), 11), 16)
last2 = tonumber(string.sub(tostring(GetAsyncWebResult), 11), 16)
last3 = tonumber(string.sub(tostring(CastSpell), 11), 16)
local function rawset3(table, value, id) end
local function protect(table) return setmetatable({}, { __index = table, __newindex = function(table, key, value) end, __metatable = false }) end
if _G.GetAsyncWebResult == nil or _G.GetUser == nil or _G.CastSpell == nil then authed = false PrintChat("<font color='#FF0000'> >> Unauthorized User <<</font>") return end
local a1 = tonumber(string.sub(tostring(_G.GetAsyncWebResult), 11), 16)
local a2 = tonumber(string.sub(tostring(_G.GetUser), 11), 16)
local a3 = tonumber(string.sub(tostring(_G.CastSpell), 11), 16)
if abs(a2-a1) > 500000 and abs(a3-a2) > 500000 then authed = false PrintChat("<font color='#FF0000'> >> Unauthorized User <<</font>") return end
if abs(a2-a1) < 500 and abs(a3-a2) < 500 then authed = false PrintChat("<font color='#FF0000'> >> Unauthorized User <<</font>") return end

local LRU = "https://raw.github.com/HeXeDMinD/Private-Repository/master/authlist.lua"
local UPDATE_TF = LIB_PATH.."FaeTmp2.txt"

function authCheck()
	DownloadFile(LRU, UPDATE_TF, authCallback)
end

function authCallback()
	f = io.open(UPDATE_TF, "rb")
	if f ~= nil then
		content = f:read("*all")
		f:close()
		os.remove(UPDATE_TF)
		if content then
			auth = string.find(content, GetUser())
			if auth or auth2 then
				authed = true
				PrintChat("<font color='#CCCCCC'> >> Authorized User <<</font>")
				return
			else
				PrintChat("<font color='#FF0000'> >> Unauthorized User <<</font>")
				authed = false
				return
			end
		end
	end
end

local Prodiction = ProdictManager.GetInstance()
local mapPosition = MapPosition()
local qRange, eRange, rRange = 465, 475, 1300
local knockUpReady, dashing = false, false
local damageAmp, qTick, eTick = 1, 0, 0
local towers, KnockedUp = {}, {}

local halfHitBoxSize = GetDistance(myHero.minBBox, myHero.maxBBox)/2
local evadingSkillShot, evadingPosition, skillshotToAdd, Allies, Enemies
local DetectedSkillshots, avoidTable, championTable = {}, {}, {}
_G.evading = false

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

function OnLoad()
	authCheck()
	createMenu()
	blockMenu()
	
	if _G.allowSpells then _G.allowSpells.Yasuo = {_W, _R} end
	
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_PHYSICAL, true)
	es = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1300, DAMAGE_PHYSICAL, true)
	enemyMinions = minionManager(MINION_ENEMY, 1300, myHero)
	jungleMinions = minionManager(MINION_JUNGLE, 1300, myHero)
	
	towersUpdate()
	focalPointCheck()

	qp = Prodiction:AddProdictionObject(_Q, 450, math.huge, 0.25, 50)
	qp2 = Prodiction:AddProdictionObject(_Q, 900, 1200, 0.25, 50)
	
	PrintChat("<font color='#CCCCCC'> >> Yasuo - The Unforgiven loaded! <<</font>")
end

function OnTick()
	if not authed then return end
	Checks()
	
	--> Blocker
	if WREADY then
		if YasuoBlock.Main.blockEnabled or YasuoBlock.Main.blockHeld then EvadeSkillShots() end
	end
	
	--> Combo
	if YasuoConfig.Basic.basicCombo then
		if ts.target and ts.target.type == myHero.type then
			if QREADY then castQ(ts.target) end
			if YasuoConfig.Basic.useE and EREADY then
				if GetDistance(ts.target) <= eRange and not TargetHaveBuff("YasuoDashWrapper", ts.target) then
					local dashPos = myHero + (Vector(ts.target) - myHero):normalized()*eRange
					local eDmg = getDamageE(ts.target)
					if (GetDistance(ts.target) > 350 and not inTurretRange(dashPos)) or ts.target.health < eDmg then 
						CastSpell(_E, ts.target)
					end
				elseif GetDistance(ts.target) <= 1200 then
					eGapClose(ts.target)
				end
			end
		elseif YasuoConfig.Basic.useE and EREADY and es.target and es.target.type == myHero.type and GetDistance(es.target) <= 1200 then
			eGapClose(es.target)
		end
	end
	
	--> Ult Cast
	if RREADY then
		for i, knockedUp in pairs(KnockedUp) do
			if YasuoConfig.Basic.basicCombo and YasuoConfig.Basic.useR then
				if (ts.target and knockedUp.champ == ts.target) or (es.target and knockedUp.champ == es.target) or (not ts.target or not es.target) then
					CastSpell(_R)
				end
			end
			if YasuoConfig.Auto.autoR and countKnockedUp(knockedUp.champ, 400) >= YasuoConfig.Auto.autoREnemies then
				CastSpell(_R)
			end
			if YasuoConfig.Kill.rKS and knockedUp.champ.health < getDamage(knockedUp.champ) then
				local critDmgQ = (GetInventorySlotItem(3031) and 1.875) or 1.5
				local criticalQ = (myHero.critChance >= 0.95 and critDmgQ) or 1
				local damage = (20*myHero:GetSpellData(_Q).level)+(myHero.totalDamage*criticalQ)
				local qDmg = myHero:CalcDamage(knockedUp.champ, damage)
				local shivDmg = (GetInventorySlotItem(3087) and myHero:CanUseSpell(GetInventorySlotItem(3087)) == READY and getDmg("STATIKK", minion, myHero)) or 0
				if knockedUp.champ.health > qDmg + shivDmg then CastSpell(_R) end
			end
		end
	end
	
	--> Harass
	if YasuoConfig.Basic.basicHarass then
		if ts.target and ts.target.type == myHero.type then
			if dashOut then
				eDashOut()
			elseif GetDistance(ts.target) <= qRange then 
				castQ(ts.target)
			elseif GetDistance(ts.target) <= 1200 then
				eGapClose(ts.target)
			end
		end
	end
	
	--> Escape
	if YasuoConfig.Basic.basicEscape then 
		local closestPoint, closestUnit = findClosestPoint(mousePos)
		local movePos = myHero + (Vector(mousePos) - myHero):normalized()*eRange
		if movePos then myHero:MoveTo(movePos.x, movePos.z) end
		if closestPoint and closestUnit then
			if GetDistance(closestUnit, mousePos) < (GetDistance(mousePos)-100) then 
				if EREADY then CastSpell(_E, closestUnit) end
			end
		end
	end
	
	--> Auto
	if not YasuoConfig.Basic.basicCombo and not YasuoConfig.Basic.basicHarass then
		if ts.target and not ts.target.dead and ts.target.visible and QREADY and YasuoConfig.Auto.autoQ then
			if YasuoConfig.Auto.autoQLogic and ((myHero.health < myHero.maxHealth*0.2 and countEnemies(ts.target, 600) <= 1) or (ts.target.health < getDmg("Q", ts.target, myHero)+getDmg("AD", ts.target, myHero))) then
				if ts.target and ts.target.type == myHero.type then castQ(ts.target) end
			elseif not YasuoConfig.Auto.autoQLogic then
				if ts.target and ts.target.type == myHero.type then castQ(ts.target) end
			end
		end
	end
	
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if enemy and not enemy.dead and enemy.visible then
			--> KillSteal
			if YasuoConfig.Kill.eKS then
				if not TargetHaveBuff("YasuoDashWrapper", enemy) and GetDistance(enemy) <= 900 then 
					eKS(enemy)
				end
			end
			--> Anti Q Waste
			if QREADY and knockUpReady and GetDistance(enemy) <= 900 then
				if qTick - GetGameTimer() <= 1.5 then castQ(enemy) end
			end
		end
	end
	
	--> Q Stacker(Champs)
	if QREADY and YasuoConfig.Auto.qStacker then
		if ts.target and not ts.target.dead and ts.target.visible and not knockUpReady then 
			if qTick - GetGameTimer() <= 7.5 then castQ(ts.target) end
		end
	end
	
	--> Farm
	if not YasuoConfig.Basic.basicCombo and not YasuoConfig.Basic.basicHarass then
		for i, minion in pairs(enemyMinions.objects) do
			if minion and minion.valid and not minion.dead and GetDistance(minion) <= 450 then
				if YasuoConfig.Farm.Farm then
					if countEnemies(myHero, 1000) <= 1 then
						if YasuoConfig.Farm.eFarm and EREADY then 
							local dashPos = myHero + (Vector(minion) - myHero):normalized()*eRange
							if minion.health < getDamageE(minion) and not inTurretRange(dashPos) and not TargetHaveBuff("YasuoDashWrapper", minion) then 
								local wallPos = myHero + (Vector(minion) - myHero):normalized()*375
								if not mapPosition:intersectsWall(Point(wallPos.x, wallPos.z)) then
									CastSpell(_E, minion)
									break
								end
							end 
						end
					end
					if YasuoConfig.Farm.qFarm and QREADY then
						local critDmgQ = (GetInventorySlotItem(3031) and 1.875) or 1.5
						local criticalQ = (myHero.critChance >= 0.95 and critDmgQ) or 1
						local damage = (20*myHero:GetSpellData(_Q).level)+(myHero.totalDamage*criticalQ)
						local qDmg = myHero:CalcDamage(minion, damage)
						local shivDmg = (GetInventorySlotItem(3087) and myHero:CanUseSpell(GetInventorySlotItem(3087)) == READY and getDmg("STATIKK", minion, myHero)) or 0
						if minion.health < qDmg + shivDmg then 
							if not knockUpReady and not dashing then
								castQ(minion)
								break
							end
						end
					end
				end
				--> Q Stacker(Minions)
				if QREADY and YasuoConfig.Auto.qStacker then
					if qTick - GetGameTimer() <= 7.5 then
						if not knockUpReady and not dashing then 
							castQ(minion)
							break
						end
					end
				end
			end
		end
		for i, minion in pairs(jungleMinions.objects) do
			if minion and minion.valid and not minion.dead and GetDistance(minion) <= 450 then
				if YasuoConfig.Farm.Farm then
					if YasuoConfig.Farm.eFarm and EREADY and countEnemies(myHero, 1000) <= 1 then 
						local dashPos = myHero + (Vector(minion) - myHero):normalized()*eRange
						if not inTurretRange(dashPos) and not TargetHaveBuff("YasuoDashWrapper", minion) then 
							local wallPos = myHero + (Vector(minion) - myHero):normalized()*375
							if not mapPosition:intersectsWall(Point(wallPos.x, wallPos.z)) then
								CastSpell(_E, minion)
								break
							end
						end 
					end
					if YasuoConfig.Farm.qFarm and QREADY then
						if not knockUpReady and not dashing then
							castQ(minion)
							break
						end
					end
				end
				--> Q Stacker(Jungle)
				if QREADY and YasuoConfig.Auto.qStacker then
					if qTick - GetGameTimer() <= 7.5 then
						if not knockUpReady and not dashing then 
							castQ(minion)
							break
						end
					end
				end
			end
		end
	end
end

function OnDraw()
	if not authed then return end
	if YasuoConfig.Draw.drawTimer then
		local qTimer = round(qTick - GetGameTimer(), 0)
		if qTimer > 0 then PrintFloatText(myHero, 0, tostring(qTimer)) end
	end
	if YasuoConfig.Draw.drawKnockup and knockUpReady then 
		--PrintFloatText(myHero, 0, "Whirlwind Ready")
		DrawText3D("Whirlwind Ready", myHero.x, myHero.y+40, myHero.z, 20, ARGB(255,255,0,0))
	end
	if YasuoConfig.Draw.drawQ then 
		DrawCircle(myHero.x, myHero.y, myHero.z, qRange, ARGBFromTable(YasuoConfig.Draw.drawQColour)) 
	end
	if YasuoConfig.Draw.drawE then 
		DrawCircle(myHero.x, myHero.y, myHero.z, eRange, ARGBFromTable(YasuoConfig.Draw.drawEColour)) 
	end
	if YasuoConfig.Draw.drawR then 
		DrawCircle(myHero.x, myHero.y, myHero.z, rRange, ARGBFromTable(YasuoConfig.Draw.drawRColour))
	end
end

function OnDash(unit, dash)
	if unit.isMe and (YasuoConfig.Basic.basicCombo or YasuoConfig.Basic.basicHarass) then
		if ts.target and GetDistance(ts.target, dash.endPos) < 175 then 
			CastSpell(_Q, ts.target.x, ts.target.z) 
			if YasuoConfig.Basic.basicHarass then dashOut = true end
		end
	end
end
 
function OnAnimation(unit, animationName)
	if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function OnGainBuff(unit, buff)
	if unit.isMe then
		if buff.name == "yasuoq" then qTick = GetGameTimer() + 10 end
		if buff.name == "yasuoq3w" then knockUpReady = true qTick = GetGameTimer() + 10 end
		if buff.name == "yasuodashscalar" then damageAmp = 1 + buff.stack*0.25 end
	end
	if unit.team ~= myHero.team and unit.type == myHero.type and (buff.type == 29 or buff.type == 30) then
		table.insert(KnockedUp, {champ = unit, endTick = buff.endT, duration = buff.duration+2})
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe then
		if buff.name == "yasuoq" then qTick = 0 end
		if buff.name == "yasuoq3w" then knockUpReady = false qTick = 0 end
	end
end

function OnUpdateBuff(unit, buff)
	if unit.isMe and buff.name == "yasuodashscalar" then damageAmp = 1 + buff.stack*0.25 end
end

function castQ(target)
	local qPred = (knockUpReady and qp2:GetPrediction(target)) or qp:GetPrediction(target)
	if qPred and GetDistance(qPred) <= qRange then
		CastSpell(_Q, qPred.x, qPred.z) 
		if YasuoConfig.Basic.basicHarass then dashOut = true end
	end
end

function eKS(target)
	local eDmg = getDamageE(target)
	if target.health < eDmg then
		if GetDistance(target) <= eRange then 
			CastSpell(_E, target)
		elseif YasuoConfig.Kill.killGapClose and GetDistance(target) <= 900 then
			eGapClose(target)
		end
	end
end

function eGapClose(target)
	local closestPoint, closestUnit = findClosestPoint(target)
	if closestPoint and not inTurretRange(closestPoint) then
		if GetTickCount() - eTick > 300 then
			if GetDistance(closestPoint, target) < (GetDistance(target)-100) and GetDistance(closestPoint, target) < 250 then 
				CastSpell(_E, closestUnit)
				eTick = GetTickCount()
			end
		end
	end
end

function eDashOut()
	local closestPoint, closestUnit = findClosestPoint(focalPoint)
	if closestPoint and not inTurretRange(closestPoint) then 
		CastSpell(_E, closestUnit)
		dashOut = false
	end
end

function Checks()
	if not tm:isReady() then return end
	
	cleanKnockedUp()
	
	if not YasuoConfig.Basic.basicHarass then dashOut = false end
	
	if knockUpReady and not TargetHaveBuff("yasuoq3w", myHero) then knockUpReady = false end
	
	es:update()
	enemyMinions:update()
	jungleMinions:update()
	
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	WREADY = (myHero:CanUseSpell(_W) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
	
	qRange = (knockUpReady and 900) or 450
	dashing = (lastAnimation == "Spell3" and true) or false
	
	ts.range = qRange + 450
	ts:update()
	
	if _G.evading then
		if not inDangerousArea(evadingSkillShot, myHero) or evadingSkillShot.endTick < GetTickCount() or (not YasuoBlock.Main.blockEnabled and not YasuoBlock.Main.blockHeld) then
			_G.evading = false
		end
	end
	
	if skillshotToAdd then AddSkillShot() end
	CleanSkills()
end

function createMenu()
	YasuoConfig = scriptConfig("Yasuo - The Unforgiven", "Yasuo_The_Unforgiven")
	--> Basic
	YasuoConfig:addParam("info", "                  Yasuo - The Unforgiven", SCRIPT_PARAM_INFO, "")
	YasuoConfig:permaShow("info")
	YasuoConfig:addSubMenu("Basic Settings", "Basic")
	YasuoConfig.Basic:addParam("basicCombo", "Basic - Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	YasuoConfig.Basic:addParam("basicHarass", "Basic - Harass", SCRIPT_PARAM_ONKEYDOWN, false, 88)
	YasuoConfig.Basic:addParam("basicEscape", "Basic - Escape[H]", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("H"))
	YasuoConfig.Basic:addParam("useE", "Use - Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Basic:addParam("useR", "Use - Last Breath", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Basic:addParam("ignoreTowers", "Ignore - Turrets", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Basic:addParam("sep", "Ignores turrets in Basic/Kill Combo", SCRIPT_PARAM_INFO, "")
	YasuoConfig.Basic:permaShow("basicEscape")
	
	--> Auto
	YasuoConfig:addSubMenu("Auto Settings", "Auto")
	YasuoConfig.Auto:addParam("autoQ", "Steel Tempest - Auto Poke[T]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("T"))
	YasuoConfig.Auto:addParam("autoQLogic", "Steel Tempest - Auto Poke Logic", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Auto:addParam("qStacker", "Steel Tempest - Auto Stack[8]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("8"))
	YasuoConfig.Auto:addParam("autoR", "Auto - Last Breath[9]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("9"))
	YasuoConfig.Auto:addParam("autoREnemies", "Last Breath - Min Knocked Up",SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	YasuoConfig.Auto:permaShow("autoQ")
	YasuoConfig.Auto:permaShow("qStacker")
	YasuoConfig.Auto:permaShow("autoR")
	
	--> Kill
	YasuoConfig:addSubMenu("Kill Settings", "Kill")
	YasuoConfig.Kill:addParam("eKS", "Kill - Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Kill:addParam("killGapClose", "Kill - Use Minions for Range", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Kill:addParam("rKS", "Kill - Last Breath[N]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("N"))
	YasuoConfig.Kill:permaShow("rKS")
	
	--> Farm
	YasuoConfig:addSubMenu("Farm Settings", "Farm")
	YasuoConfig.Farm:addParam("Farm", "Farm - Toggle[G]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("G"))
	YasuoConfig.Farm:addParam("qFarm", "Farm - Steel Tempest", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Farm:addParam("eFarm", "Farm - Sweeping Blade", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Farm:permaShow("Farm")
	YasuoConfig.Farm.Farm = false
	--> Draw
	YasuoConfig:addSubMenu("Draw Settings", "Draw")
	YasuoConfig.Draw:addParam("drawTimer", "Draw - Stack Timer", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Draw:addParam("drawKnockup", "Draw - Knock Up Ready", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Draw:addParam("sep1", "", SCRIPT_PARAM_INFO, "")
	YasuoConfig.Draw:addParam("drawQ", "Draw - Steel Tempest", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Draw:addParam("drawQColour", "Draw - Q Colour", SCRIPT_PARAM_COLOR, {255, 255, 0, 0})
	YasuoConfig.Draw:addParam("drawE", "Draw - Sweeping Blade", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Draw:addParam("drawEColour", "Draw - E Colour", SCRIPT_PARAM_COLOR, {255, 0, 255, 0})
	YasuoConfig.Draw:addParam("drawR", "Draw - Last Breath", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Draw:addParam("drawRColour", "Draw - R Colour", SCRIPT_PARAM_COLOR, {255, 0, 0, 255})
end

function ARGBFromTable(table)
	return ARGB(table[1], table[2], table[3], table[4])
end

function cleanKnockedUp()
	local i = 1
	while i <= #KnockedUp do
		knockedUp = KnockedUp[i]
		if GetGameTimer() > knockedUp.endTick then
			table.remove(KnockedUp, i)
		else
			i = i + 1
		end
	end
end

function findClosestPoint(target)
	local closestPoint = nil
	local currentPoint = nil
	for i, minion in pairs(enemyMinions.objects) do
		if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange and not TargetHaveBuff("YasuoDashWrapper", minion) then
			currentPoint = myHero + (Vector(minion) - myHero):normalized()*eRange
			if closestPoint == nil then
				closestPoint = currentPoint
				closestUnit = minion
			elseif GetDistance(currentPoint, target) < GetDistance(closestPoint, target) then
				closestPoint = currentPoint
				closestUnit = minion
			end
		end
	end
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if enemy and not enemy.dead and enemy.visible and GetDistance(enemy) <= eRange and not TargetHaveBuff("YasuoDashWrapper", enemy) then
			if (ts.target and enemy ~= ts.target) or not ts.target then
				currentPoint = myHero + (Vector(enemy) - myHero):normalized()*eRange
				if closestPoint == nil then
					closestPoint = currentPoint
					closestUnit = enemy
				elseif GetDistance(currentPoint, target) < GetDistance(closestPoint, target) then
					closestPoint = currentPoint
					closestUnit = enemy
				end
			end
		end
	end
	return closestPoint, closestUnit
end

function getDamage(target)
	local critDmg = (GetInventorySlotItem(3031) and 2.25) or 1.8
	local critical = (myHero.critChance >= 0.95 and critDmg) or 1
	local aDmg = getDmg("AD", target, myHero)*critical
	local critDmgQ = (GetInventorySlotItem(3031) and 1.875) or 1.5
	local criticalQ = (myHero.critChance >= 0.95 and critDmgQ) or 1
	local damage = (20*myHero:GetSpellData(_Q).level)+(myHero.totalDamage*criticalQ)
	local qDmg = myHero:CalcDamage(target, damage)
	local eDmg = (EREADY and getDamageE(target)) or 0
	local rDmg = (RREADY and getDmg("R", target, myHero)) or 0
	local bladeDmg = (GetInventorySlotItem(3153) and myHero:CanUseSpell(GetInventorySlotItem(3153)) == READY and getDmg("RUINEDKING", target, myHero)) or 0
	local cutlassDmg = (GetInventorySlotItem(3144) and myHero:CanUseSpell(GetInventorySlotItem(3144)) == READY and getDmg("BWC", target, myHero)) or 0
	local tiamatDmg = (GetInventorySlotItem(3077) and myHero:CanUseSpell(GetInventorySlotItem(3077)) == READY and getDmg("TIAMAT", target, myHero)) or 0
	local hydraDmg = (GetInventorySlotItem(3074) and myHero:CanUseSpell(GetInventorySlotItem(3074)) == READY and getDmg("HYDRA", target, myHero)) or 0
	local shivDmg = (GetInventorySlotItem(3087) and myHero:CanUseSpell(GetInventorySlotItem(3087)) == READY and getDmg("STATIKK", target, myHero)) or 0
	local sheenDmg = (GetInventorySlotItem(3057) and getDmg("SHEEN", target, myHero)) or 0
	local trinityDmg = (GetInventorySlotItem(3078) and getDmg("TRINITY", target, myHero)) or 0

	return aDmg*2 + (qDmg*2+ eDmg + rDmg) + (bladeDmg + cutlassDmg + tiamatDmg + hydraDmg + shivDmg + sheenDmg + trinityDmg)
end

function getDamageE(target)
	return myHero:CalcMagicDamage(target, 50+(20*myHero:GetSpellData(_E).level)*damageAmp + (myHero.ap*0.6))
end

function focalPointCheck()
	if player.team == TEAM_RED then
		focalPoint = Point(13936.64, 14174.86) -- y = 184.97, RED BASE
	else
		focalPoint = Point(28.58, 267.16) -- y = 184.62, BLUE BASE
	end
end

function countKnockedUp(point, range)
	local ChampCount = 0
	for i, knockedUp in ipairs(KnockedUp) do
		local champ = knockedUp.champ
		if champ and not champ.dead and GetDistance(champ, point) <= range then
			ChampCount = ChampCount + 1
		end
	end		
	return ChampCount
end

function countEnemies(point, range)
	local ChampCount = 0
	for i, champ in ipairs(GetEnemyHeroes()) do
		if champ and not champ.dead and GetDistance(champ, point) <= range then
			ChampCount = ChampCount + 1
		end
	end		
	return ChampCount
end

function round(num, idp)
  local mult = 10^(idp or 0)
  return math.floor(num * mult + 0.5) / mult
end

--> Tower Code
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

function inTurretRange(pos)
	if YasuoConfig.Basic.ignoreTowers then return false end
	if YasuoConfig.Basic.basicCombo then 
		if ts.target and ts.target.type == myHero.type then
			if ts.target.health < (getDamage(ts.target)*0.75) then return false end
		end
	end

	local check = false
	for i, tower in ipairs(towers) do
		if tower and (tower.health > 0 or not tower.dead) then
			if GetDistance(tower, pos) <= 890 then check = true end
		else
			table.remove(towers, i)
		end
	end
	return check
end

--[[ Blocker ]]--

function blockMenu()
	generateTables()
	
	if _G.allowSpells then _G.allowSpells.Yasuo = {_W} end
	YasuoBlock = scriptConfig("Yasuo - Particle Ninja", "Yasuo_Particle_Ninja")
	YasuoBlock:addSubMenu("Main Settings", "Main")
	YasuoBlock.Main:addParam("blockHeld", "Block - Held", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	YasuoBlock.Main:addParam("blockEnabled", "Block - Toggle", SCRIPT_PARAM_ONOFF, true)
	YasuoBlock.Main:permaShow("blockEnabled")
	
	YasuoBlock:addSubMenu("Targeted Skill Settings", "Targeted")
	for i, skill in pairs(avoidTable) do
		YasuoBlock.Targeted:addParam(skill.spellName.."skill", "Skill Active - "..skill.spellName, SCRIPT_PARAM_ONOFF, true)
	end
	YasuoBlock:addSubMenu("Skillshot Settings", "Skillshots")
	for i, skillshot in pairs(championTable) do
		YasuoBlock.Skillshots:addParam(skillshot.spellName.."skill", "Skill Active - "..skillshot.spellName, SCRIPT_PARAM_ONOFF, true)
	end
	PrintChat("<font color='#CCCCCC'> >> Yasuo - Particle Ninja loaded! <<</font>")
end

function OnProcessSpell(unit, spell)
	if unit.team ~= player.team and not player.dead then
		if YasuoBlock.Main.blockEnabled or YasuoBlock.Main.blockHeld then
			if spell.target == myHero then
				for i, ability in pairs(avoidTable) do
					if spell.name == ability.spellName and YasuoBlock.Targeted[ability.spellName.."skill"] then
						CastSpell(_W, unit.x, unit.z)
					end
				end
			end
			for i, skillshot in pairs(championTable) do
				if skillshot.charName == unit.charName then
					if skillshot.spellName == spell.name and YasuoBlock.Skillshots[skillshot.spellName.."skill"] then
						startPosition = Point(unit.x, unit.z)
						endPosition = Point(spell.endPos.x, spell.endPos.z)
						endTick = GetTickCount() + skillshot.spellDelay + skillshot.range / skillshot.projectileSpeed * 1000
						endPosition = GetExtendedEndPos(startPosition, endPosition, skillshot.range)
						table.insert(DetectedSkillshots, {startPosition = startPosition, endPosition = endPosition, skillshot = skillshot, endTick = endTick, collision = skillshot.collision, range = skillshot.range, width = skillshot.radius, caster = unit})
					end
				end
			end
		end
	end
end

function EvadeSkillShots()
	for i, skillshot in ipairs(DetectedSkillshots) do
		if skillshot and inDangerousArea(skillshot, myHero) then
			if skillshot.collision then
				if not minionCollision(skillshot.caster, skillshot.width, skillshot.range) then
					CastSpell(_W, skillshot.startPosition.x, skillshot.startPosition.y)
					evadingSkillShot = skillshot
				end
			else
				CastSpell(_W, skillshot.startPosition.x, skillshot.startPosition.y)
				evadingSkillShot = skillshot
			end
		end
	end
end

function minionCollision(predic, width, range)
	for _, minion in pairs(enemyMinions.objects) do
		if predic ~= nil and player:GetDistance(minion) < range then
			ex, ez = player.x, player.z
			tx, tz = predic.x, predic.z
			dx = ex - tx
			dz = ez - tz
			if dx ~= 0 then
				m = dz/dx
				c = ez - m*ex
			end
			mx, mz = minion.x, minion.z
			distanc = (math.abs(mz - m*mx - c))/(math.sqrt(m*m+1))
			if distanc < width and math.sqrt((tx - ex)*(tx - ex) + (tz - ez)*(tz - ez)) > math.sqrt((tx - mx)*(tx - mx) + (tz - mz)*(tz - mz)) then
				return true
			end
		end
	end
	return false
end

function GetExtendedEndPos(startPos, endPos, distance)
	direction = endPos - startPos
	direction:normalize()
	direction = direction * distance
	return startPos + direction
end

function inDangerousArea(skillshot, unit)
	playerPoint = Point(unit.x, unit.z)
	if skillshot then
		skillshotSegment = LineSegment(skillshot.startPosition, skillshot.endPosition)
		projection = playerPoint:perpendicularFoot(Line(skillshot.startPosition, skillshot.endPosition))
		return playerPoint:distance(projection) <= skillshot.skillshot.radius + halfHitBoxSize and skillshotSegment:distance(projection) < 3
	end
end

function CleanSkills()
	local i = 1
	local detectedSkillshot
	while i <= #DetectedSkillshots do
		detectedSkillshot = DetectedSkillshots[i]
		if detectedSkillshot.endTick < GetTickCount() then
			table.remove(DetectedSkillshots, i)
		else
			i = i + 1
		end
	end
end

function AddSkillShot()
	if not skillshotToAdd.object.valid then
		skillshotToAdd = nil
		return
	end
	
	if GetTickCount() - skillshotToAdd.startTick > 2 * GetLatency() then
		actualPosition = Point(skillshotToAdd.object.x, skillshotToAdd.object.z)
		endPosition = GetExtendedEndPos(skillshotToAdd.startPosition, actualPosition, skillshotToAdd.skillshot.range)
		table.insert(DetectedSkillshots, {startPosition = skillshotToAdd.startPosition, endPosition = endPosition, skillshot = skillshotToAdd.skillshot, startTick = skillshotToAdd.startTick, endTick = skillshotToAdd.endTick})
		skillshotToAdd = nil
	end
end

function generateTables()
	AvoidList = {
		["AkaliQ"] = {charName = "Akali", spellName = "AkaliMota", dangerous = false},
		["AnnieQ"] = {charName = "Annie", spellName = "Disintegrate", dangerous = false},
		["CaitlynR"] = {charName = "Caitlyn", spellName = "CaitlynAceintheHole", dangerous = true},
		["CassiopeiaE"] = {charName = "Cassiopeia", spellName = "CassiopeiaTwinFang", dangerous = false},
		["KalyeQ"] = {charName = "Kayle", spellName = "JudicatorReckoning", dangerous = true},
		["PantheonQ"] = {charName = "Pantheon", spellName = "Pantheon_Throw", dangerous = false},
		["SonaAttack"] = {charName = "Sona", spellName = "SonaSongofDiscordAttack", dangerous = false},
		["SonaAttack2"] = {charName = "Sona", spellName = "SonaHymnofValorAttack", dangerous = false},
		["SonaAttack3"] = {charName = "Sona", spellName = "SonaAriaofPerseveranceAttack", dangerous = false},
		["SonaAttack4"] = {charName = "Sona", spellName = "SonaPowerChordMissile", dangerous = false},
		["SonaQ"] = {charName = "Sona", spellName = "SonaHymnofValor", dangerous = false},
		["SyndraR"] = {charName = "Syndra", spellName = "SyndraR", dangerous = true},
		["TristnaE"] = {charName = "Tristana", spellName = "DetonatingShot", dangerous = false},
		["TristnaR"] = {charName = "Tristana", spellName = "BusterShot", dangerous = true},
		["TaricE"] = {charName = "Taric", spellName = "Dazzle", dangerous = true},
		["SionQ"] = {charName = "Sion", spellName = "CrypticGaze", dangerous = true},
		["VayneE"] = {charName = "Vayne", spellName = "VayneCondemn", dangerous = true},
		["NunuE"] = {charName = "Nunu", spellName = "IceBlast", dangerous = false},
		["MalphiteQ"] = {charName = "Malphite", spellName = "SeismicShard", dangerous = false},
		["KassadinQ"] = {charName = "Kassadin", spellName = "NullLance", dangerous = false},
		["ShacoE"] = {charName = "Shaco", spellName = "TwoShivPoison", dangerous = false},
		["TeemoQ"] = {charName = "Teemo", spellName = "BlindingDart", dangerous = false},
		["VeigarR"] = {charName = "Veigar", spellName = "VeigarPrimordialBurst", dangerous = true},
		["DFG"] = {charName = "DFG", spellName = "DeathfireGrasp", dangerous = true},
		["LeblancQ"] = {charName = "Leblanc", spellName = "LeblancChaosOrb", dangerous = false},
		["LeblancQM"] = {charName = "Leblanc", spellName = "LeblancChaosOrbM", dangerous = false},
		["FiddleSticksE"] = {charName = "FiddleSticks", spellName = "FiddlesticksDarkWind", dangerous = false},
		["AniviaE"] = {charName = "Anivia", spellName = "Frostbite", dangerous = false},
		["BrandR"] = {charName = "Brand", spellName = "BrandWildfire", dangerous = true},
		["RyzeQ"] = {charName = "Ryze", spellName = "Overload", dangerous = false},
		["RyzeE"] = {charName = "Ryze", spellName = "SpellFlux", dangerous = false},
		["TwistedFateG"] = {charName = "TwistedFate", spellName = "GoldCardAttack", dangerous = true},
		["TwistedFateR"] = {charName = "TwistedFate", spellName = "RedCardAttack", dangerous = true},
		["TwistedFateG2"] = {charName = "TwistedFate", spellName = "GoldCardPreAttack", dangerous = true},
		["TwistedFateR2"] = {charName = "TwistedFate", spellName = "RedCardPreAttack", dangerous = true},
		["DravenQCrit"] = {charName = "Draven", spellName = "DravenSpinningAttackCrit", dangerous = false},
		["EliseQ"] = {charName = "Elise", spellName = "EliseHumanQ", dangerous = false},
		["GangplankQ"] = {charName = "Gangplank", spellName = "Parley", dangerous = false},
		["SwainQ"] = {charName = "Swain", spellName = "SwainTorment", dangerous = false},
		["RengarE"] = {charName = "Rengar", spellName = "RengarE", dangerous = false},
		["RengarE2"] = {charName = "Rengar", spellName = "RengarEFinal", dangerous = false},
		["RengarE3"] = {charName = "Rengar", spellName = "RengarEFinalMAX", dangerous = true},
		["MissFortuneQ"] = {charName = "MissFortune", spellName = "MissFortuneRicochetShot", dangerous = false},
		["MissFortuneQ2"] = {charName = "MissFortune", spellName = "MissFortuneRShotExtra", dangerous = true},
		["KatarinaQ"] = {charName = "Katarina", spellName = "KatarinaQ", dangerous = false},
		["KogmawQ"] = {charName = "Kogmaw", spellName = "KogMawCausticSpittle", dangerous = false},
		["ViktorQ"] = {charName = "Viktor", spellName = "ViktorPowerTransfer", dangerous = false},
		["UrgotQ"] = {charName = "Urgot", spellName = "UrgotHeatseekingHomeMissile", dangerous = true},
	}
	
	Champions = {
		--[[	Additional	]]--
		["Ahri"] = {charName = "Ahri", skillshots = {
			["Orb of Deception"] = {spellName = "AhriOrbofDeception", spellDelay = 230, projectileSpeed = 1485, range = 900, radius = 100, dangerous = false, collision = false},
			["Charm"] = {spellName = "AhriSeduce", spellDelay = 250, projectileSpeed = 1600, range = 1000, radius = 60, dangerous = true, collision = true},
		}},
		["Ashe"] = {charName = "Ashe", skillshots = { 
			["Volley"] = {spellName = "Volley", spellDelay = 250, projectileSpeed = 1500, range = 1200, radius = 100, dangerous = false, collision = true},
			["Hawkshot"] = {spellName = "AsheSpiritOfTheHawk", spellDelay = 250, projectileSpeed = 1400, range = 6000, radius = 300, dangerous = false, collision = false},
			["Enchanted Arrow"] = {spellName = "EnchantedCrystalArrow", spellDelay = 250, projectileSpeed = 1600, range = 25000, radius = 130, dangerous = true, collision = false},
		}},
		["Brand"] = {charName = "Brand", skillshots = { 
			["Sear"] = {spellName = "BrandBlaze", spellDelay = 250, projectileSpeed = 1200, range = 1100, radius = 210, dangerous = false, collision = true},
		}},
		["Caitlyn"] = {charName = "Caitlyn", skillshots = { 
			["Piltover Peacemaker"] = {spellName = "CaitlynPiltoverPeacemaker", spellDelay = 650, projectileSpeed = 2200, range = 1300, radius = 90, dangerous = false, collision = false}, 
			["90 Caliber Net"] = {spellName = "CaitlynEntrapment", spellDelay = 500, projectileSpeed = 2000, range = 80, radius = 20, dangerous = false, collision = true}, 
		}},
		["DrMundo"] = {charName = "DrMundo", skillshots = { 
			["Infected Cleaver"] = {spellName = "InfectedCleaverMissileCast", spellDelay = 200, projectileSpeed = 1500, range = 1050, radius = 100, dangerous = false, collision = true},
		}},
		["Ezreal"] = {charName = "Ezreal", skillshots = { 
			["Mystic Shot"] = {spellName = "EzrealMysticShot", spellDelay = 500, projectileSpeed = 1200, range = 1200, radius = 120, dangerous = false, collision = true},
			["Essence Flux"] = {spellName = "EzrealEssenceFlux", spellDelay = 500, projectileSpeed = 1200, range = 1100, radius = 210, dangerous = false},
			["Trueshot Barrage"] = {spellName = "EzrealTrueshotBarrage", spellDelay = 1000, projectileSpeed = 2000, range = 20000, radius = 160, dangerous = true, collision = false},
		}},
		["Galio"] = {charName = "Galio", skillshots = { 
			["Resolute Smite"] = {spellName = "GalioResoluteSmite", spellDelay = 250, projectileSpeed = 1300, range = 900, radius = 210, dangerous = false, collision = false},
			["Righteous Gust"] = {spellName = "GalioRighteousGust", spellDelay = 250, projectileSpeed = 1200, range = 1180, radius = 90, dangerous = false, collision = false},
		}},
		["Gragas"] = {charName = "Gragas", skillshots = { 
			["Barrel Roll"] = {spellName = "GragasBarrelRoll", spellDelay = 250, projectileSpeed = 1000, range = 950, radius = 210, dangerous = false, collision = false},
			["Explosive Cask"] = {spellName = "GragasExplosiveCask", spellDelay = 250, projectileSpeed = 1800, range = 1100, radius = 350, dangerous = true, collision = false},
		}},
		["Graves"] = {charName = "Graves", skillshots = { 
			["Cluster Shot"] = {spellName = "GravesClusterShot", spellDelay = 250, projectileSpeed = 2000, range = 700, radius = 210, dangerous = false, collision = false},
			["Charge Shot"] = {spellName = "GravesChargeShot", spellDelay = 250, projectileSpeed = 1200, range = 1000, radius = 90, dangerous = true, collision = false},
		}},
		["Heimerdinger"] = {charName = "Heimerdinger", skillshots = { 
			["Grenade"] = {spellName = "HeimerdingerW", spellDelay = 250, projectileSpeed = 1800, range = 1200, radius = 90, dangerous = true, collision = true},
			["Missile Barrage"] = {spellName = "HeimerdingerE", spellDelay = 250, projectileSpeed = 2500, range = 925, radius = 120, dangerous = false, collision = false},
			["Ult Grenade"] = {spellName = "HeimerdingerUltWDummySpell", spellDelay = 250, projectileSpeed = 1800, range = 1200, radius = 90, dangerous = true, collision = true},
			["Ult Missile Barrage"] = {spellName = "HeimerdingerUltEDummySpell", spellDelay = 250, projectileSpeed = 2500, range = 925, radius = 120, dangerous = true, collision = false},
		}},
		["Diana"] = {charName = "Diana", skillshots = { 
			["DianaArc"] = {spellName = "DianaArc", spellDelay = 250, projectileSpeed = 1750, range = 900, radius = 150, dangerous = false, collision = false},
		}},
		["Sivir"] = {charName = "Sivir", skillshots = { 
			["Boomerang Blade"] = {spellName = "SivirQ", spellDelay = 500, projectileSpeed = 1350, range = 1075, radius = 150, dangerous = false, collision = false},
		}},
		["Talon"] = {charName = "Talon", skillshots = { 
			["Rake"] = {spellName = "TalonRake", spellDelay = 500, projectileSpeed = 900, range = 650, radius = 100, dangerous = false, collision = false},
		}},
		["Twisted Fate"] = {charName = "TwistedFate", skillshots = { 
			["WildCards"] = {spellName = "WildCards", spellDelay = 500, projectileSpeed = 1450, range = 1500, radius = 210, dangerous = false, collision = false},
		}},
		["Twitch"] = {charName = "Twitch", skillshots = { 
			["Venom Cask"] = {spellName = "TwitchVenomCask", spellDelay = 500, projectileSpeed = 1750, range = 950, radius = 275, dangerous = false, collision = false},
		}},
		["Viktor"] = {charName = "Viktor", skillshots = { 
			["Deathray"] = {spellName = "ViktorDeathRay", spellDelay = 250, projectileSpeed = 1200, range = 550, radius = 100, dangerous = false, collision = false},
		}},
    ["Jayce"] = {charName = "Jayce", skillshots = {
			["JayceShockBlast"] = {spellName = "JayceShockBlast", spellDelay = 250, projectileSpeed = 1450, range = 1050, radius = 70, dangerous = false, collision = true},
			["JayceShockBlastCharged"] = {spellName = "JayceShockBlast", spellDelay = 250, projectileSpeed = 2350, range = 1600, radius = 70, dangerous = true, collision = true},
    }},
    ["Karma"] = {charName = "Karma", skillshots = {
			["KarmaQ"] = {spellName = "KarmaQ", spellDelay = 250, projectileSpeed = 1700, range = 1050, radius = 90, dangerous = true, collision = true},
    }},
    ["Khazix"] = {charName = "Khazix", skillshots = {
			["KhazixW"] = {spellName = "KhazixW", spellDelay = 250, projectileSpeed = 1700, range = 1025, radius = 70, dangerous = true, collision = true},
        ["khazixwlong"] = {spellName = "khazixwlong", spellDelay = 250, projectileSpeed = 1700, range = 1025, radius = 70, dangerous = true, collision = true},
    }},
    ["Leblanc"] = {charName = "Leblanc", skillshots = {
			["Ethereal Chains"] = {spellName = "LeblancSoulShackle", spellDelay = 250, projectileSpeed = 1600, range = 960, radius = 70, dangerous = true, collision = true},
        ["Ethereal Chains R"] = {spellName = "LeblancSoulShackleM", spellDelay = 250, projectileSpeed = 1600, range = 960, radius = 70, dangerous = true, collision = true},
    }},
		["Leona"] = {charName = "Leona", skillshots = {
			["Zenith Blade"] = {spellName = "LeonaZenithBlade", spellDelay = 250, projectileSpeed = 2000, range = 900, radius = 80, dangerous = true, collision = false},
    }},
		["Lissandra"] = {charName = "Lissandra", skillshots = {
			["Ice Shard"] = {spellName = "LissandraQ", spellDelay = 250, projectileSpeed = 2300, range = 725, radius = 40, dangerous = false, collision = true},
			["Glacial Path"] = {spellName = "LissandraE", spellDelay = 250, projectileSpeed = 850, range = 1050, radius = 80, dangerous = true, collision = false},
    }},
		["Lucian"] = {charName = "Lucian", skillshots = {
			["LucianW"] =  {spellName = "LucianW", spellDelay = 300, projectileSpeed = 1600, range = 1000, radius = 80, dangerous = false, collision = true},
			["LucianR"] =  {name = "LucianR", spellName = "LucianR", spellDelay = 350, projectileSpeed = 1600, range = 1400, radius = 65, dangerous = true, collision = false},
    }},
		["MissFortune"] = {charName = "MissFortune", skillshots = {
			["MissFortuneBulletTime"] = {spellName = "MissFortuneBulletTime", spellDelay = 250, projectileSpeed = 2000, range = 1400, radius = 300, dangerous = true, collision = false},
    }},
		["Nami"] = {charName = "Nami", skillshots = {
			["NamiQ"] = {spellName = "NamiQ", spellDelay = 250, projectileSpeed = 1500, range = 1625, radius = 150, dangerous = true, collision = false},
			["NamiR"] = {spellName = "NamiR", spellDelay = 250, projectileSpeed = 1200, range = 2550, radius = 225, dangerous = true, collision = false},
    }},
		["Nocturne"] = {charName = "Nocturne", skillshots = {
			["NocturneDuskbringer"] =  {spellName = "NocturneDuskbringer", spellDelay = 250, projectileSpeed = 1400, range = 1125, radius = 60, dangerous = false, collision = false},
    }},
    ["Olaf"] = {charName = "Olaf", skillshots = {
			["Undertow"] = {spellName = "OlafAxeThrow", spellDelay = 250, projectileSpeed = 1600, range = 1000, radius = 90, dangerous = true, collision = false},
    }},
    ["Orianna"] = {charName = "Orianna", skillshots = {
			["OrianaIzunaCommand"] = {spellName = "OrianaIzunaCommand", spellDelay = 250,projectileSpeed = 1200, range = 2000, radius = 80, dangerous = true, collision = false},
    }},
		["Quinn"] = {charName = "Quinn", skillshots = {
			["QuinnQ"] = {spellName = "QuinnQ", spellDelay = 250, projectileSpeed = 1550, range = 1050, radius = 80, dangerous = false, collision = true},
    }},
		["Riven"] = {charName = "Riven", skillshots = {
			["rivenizunablade"] = {spellName = "rivenizunablade", spellDelay = 250, projectileSpeed = 1450, range = 1025, radius = 225, dangerous = true, collision = false},
    }},
		["Rumble"] = {charName = "Rumble", skillshots = {
			["RumbleGrenade"] = {spellName = "RumbleGrenade", spellDelay = 250, projectileSpeed = 2000, range = 950, radius = 90, dangerous = true, collision = true},
    }},
		["Urgot"] = {charName = "Urgot", skillshots = {
			["Acid Hunter"] = {spellName = "UrgotHeatseekingLineMissile", spellDelay = 175, projectileSpeed = 1600, range = 1000, radius = 60, dangerous = false, collision = true},
			["Plasma Grenade"] = {spellName = "UrgotPlasmaGrenade", spellDelay = 250, projectileSpeed = 1750, range = 900, radius = 250, dangerous = true, collision = false},
    }},
		["Yasuo"] = {charName = "Yasuo", skillshots = {
			["Yasuo Whirlwind"] = {spellName = "yasuoq3wautosmartcast", spellDelay = 250, projectileSpeed = 1600, range = 900, radius = 80, dangerous = true, collision = false},
		}},
		["Aatrox"] = {charName = "Aatrox", skillshots = {
			["Blade of Torment"] = {spellName = "AatroxE", spellDelay = 250, projectileSpeed = 1200, range = 1075, radius = 100, dangerous = false, collision = false},
		}},
		["Amumu"] = {charName = "Amumu", skillshots = {
			["Bandage Toss"] = {spellName = "BandageToss", spellDelay = 250, projectileSpeed = 2000, range = 1100, radius = 80, dangerous = true, collision = true},
		}},
		["Anivia"] = {charName = "Anivia", skillshots = {
			["Flash Frost"] = {spellName = "FlashFrostSpell", spellDelay = 250, projectileSpeed = 850, range = 1100, radius = 110, dangerous = true, collision = false},
		}},
		["Blitzcrank"] = {charName = "Blitzcrank", skillshots = {
			["Rocket Grab"] = {"RocketGrabMissile", spellDelay = 250, projectileSpeed = 1800, range = 1050, radius = 70, dangerous = true, collision = true},
		}},
		["Draven"] = {charName = "Draven", skillshots = {
			["Stand Aside"] = {spellName = "DravenDoubleShot", spellDelay = 250, projectileSpeed = 1400, range = 1100, radius = 130, dangerous = true, collision = false},
			["DravenR"] = {spellName = "DravenRCast", spellDelay = 500, projectileSpeed = 2000, range = 25000, radius = 160, dangerous = true, collision = false},
		}},
		["Elise"] = {charName = "Elise", skillshots = {
			["Cocoon"] = {spellName = "EliseHumanE", spellDelay = 250, projectileSpeed = 1450, range = 1100, radius = 70, dangerous = true, collision = true},
		}},
		["Fizz"] = {charName = "Fizz", skillshots = {
			["Fizz Ultimate"] = {spellName = "FizzMarinerDoom", spellDelay = 250, projectileSpeed = 1350, range = 1275, radius = 80, dangerous = true, collision = false},
		}},
		["Jinx"] = {charName = "Jinx", skillshots = {
			["W"] =  {spellName = "JinxWMissile", spellDelay = 600, projectileSpeed = 3300, range = 1450, radius = 70, dangerous = false, collision = true},
			["R"] =  {spellName = "JinxRWrapper", spellDelay = 600, projectileSpeed = 2200, range = 20000, radius = 120, dangerous = true, collision = false},
		}}, 
		["Lee Sin"] = {charName = "LeeSin", skillshots = {
			["Sonic Wave"] = {spellName = "BlindMonkQOne", spellDelay = 250, projectileSpeed = 1800, range = 1100, radius = 70, dangerous = true, collision = true},
		}},
		["Lux"] = {charName = "Lux", skillshots = {
			["Light Binding"] =  {spellName = "LuxLightBinding", spellDelay = 250, projectileSpeed = 1200, range = 1300, radius = 80, dangerous = true, collision = true},
			["Lux LightStrike Kugel"] = {spellName = "LuxLightStrikeKugel", spellDelay = 250, projectileSpeed = 1400, range = 1100, radius = 275, dangerous = false, collision = false},
		}},
		["Morgana"] = {charName = "Morgana", skillshots = {
			["Dark Binding Missile"] = {spellName = "DarkBindingMissile", spellDelay = 250, projectileSpeed = 1200, range = 1300, radius = 80, dangerous = true, collision = true},
		}},
		["Nautilus"] = {charName = "Nautilus", skillshots = {
			["Dredge Line"] = {spellName = "NautilusAnchorDrag", spellDelay = 250, projectileSpeed = 2000, range = 1080, radius = 80, dangerous = true, collision = true},
		}},
		["Nidalee"] = {charName = "Nidalee", skillshots = {
			["Javelin Toss"] = {spellName = "JavelinToss", spellDelay = 125, projectileSpeed = 1300, range = 1500, radius = 60, dangerous = true, collision = true},
		}},
		["Sejuani"] = {charName = "Sejuani", skillshots = {
			["SejuaniR"] = {spellName = "SejuaniGlacialPrisonCast", spellDelay = 250, projectileSpeed = 1600, range = 1200, radius = 110, dangerous = true, collision = false},
		}},
		["Sona"] = {charName = "Sona", skillshots = {
			["Crescendo"] = {spellName = "SonaCrescendo", spellDelay = 250, projectileSpeed = 2400, range = 1000, radius = 140, dangerous = true, collision = false},		
		}},
		["Thresh"] = {charName = "Thresh", skillshots = {
			["ThreshQ"] = {spellName = "ThreshQ", spellDelay = 500, projectileSpeed = 1900, range = 1100, radius = 65, dangerous = true, collision = true},
		}},
		["Varus"] = {charName = "Varus", skillshots = {
			["Varus Q Missile"] = {spellName = "VarusQ", spellDelay = 0, projectileSpeed = 1900, range = 1600, radius = 70, dangerous = true, collision = false},
			["VarusR"] = {spellName = "VarusR", spellDelay = 250, projectileSpeed = 1950, range = 1250, radius = 100, dangerous = true, collision = false},
		}},
		["Zyra"] = {charName = "Zyra", skillshots = {
			["Grasping Roots"] = {spellName = "ZyraGraspingRoots", spellDelay = 250, projectileSpeed = 1150, range = 1150, radius = 70, dangerous = true, collision = false},
			["Zyra Passive Death"] = {spellName = "zyrapassivedeathmanager", spellDelay = 500, projectileSpeed = 2000, range = 1474, radius = 60, dangerous = true, collision = false},
		}},
		["Ziggs"] = {charName = "Ziggs", skillshots = {
			["ZiggsQ"] = {spellName = "ZiggsQ", spellDelay = 250, projectileSpeed = 1750, range = 1400, radius = 90, dangerous = false, collision = false},
			["ZiggsW"] = {spellName = "ZiggsW", spellDelay = 250, projectileSpeed = 1750, range = 1000, radius = 150, dangerous = false, collision = false},
			["ZiggsE"] = {spellName = "ZiggsE", spellDelay = 250, projectileSpeed = 1750, range = 900, radius = 200, dangerous = false, collision = false},
			["ZiggsR"] = {spellName = "ZiggsR", spellDelay = 375, projectileSpeed = 1750, range = 5300, radius = 500, dangerous = true, collision = false},
		}}
	}
	for i, avoidChamp in pairs(AvoidList) do
		if avoidChamp.charName == "DFG" then
			local skill = {charName = avoidChamp.charName, spellName = avoidChamp.spellName, dangerous = avoidChamp.dangerous}
			table.insert(avoidTable, skill)
		end
	end
	for i, enemy in ipairs(GetEnemyHeroes()) do
		for i, avoidChamp in pairs(AvoidList) do
			if avoidChamp.charName == enemy.charName then
				local skill = {charName = avoidChamp.charName, spellName = avoidChamp.spellName, dangerous = avoidChamp.dangerous}
				table.insert(avoidTable, skill)
			end
		end
		for i, skillShotChampion in pairs(Champions) do
			if skillShotChampion.charName == enemy.charName then
				for i, skillshot in pairs(skillShotChampion.skillshots) do
					local skill = {charName = skillShotChampion.charName, spellName = skillshot.spellName, spellDelay = skillshot.spellDelay, projectileSpeed = skillshot.projectileSpeed, range = skillshot.range, radius = skillshot.radius, dangerous = skillshot.dangerous, collision = skillshot.collision}
					table.insert(championTable, skill)
				end
			end
		end
	end
end