<?php exit() ?>--by ragequit 71.63.104.40
local Names = {
 "omfgabriel",
}

local f = false
for _, Name in pairs(Names) do
 if GetUser() == Name then
  f = true
 end
end
if myHero.charName ~= "Draven" or f = false then return end

if f then PrintChat("Welcome Alpha Testers") end

class 'Plugin'


disableRangeDraw = true
local reticles = {}
local towers = {} 
local qStacks = 0
local closestReticle
local qBuff = 0
local stopped = false
local qRad = 150
local qParticles = {"Draven_Q_mis",
								"Draven_Q_mis_bloodless",
								"Draven_Q_mis_shadow",
								"Draven_Q_mis_shadow_bloodless",
								"Draven_Qcrit_mis",
								"Draven_Qcrit_mis_bloodless",
								"Draven_Qcrit_mis_shadow",
								"Draven_Qcrit_mis_shadow_bloodless" }
local SkillQ
local SkillW
local SkillE
local SkillR	
reticleDetectionRange = 1200 

function OnLoad()  

reticleDetectionRange = 1200  
   
        --[[ Towers List ]]
    for i = 1, objManager.iCount, 1 do
        local obj = objManager:getObject(i) 
        if obj ~= nil and string.find(obj.type, "obj_Turret") ~= nil and obj.health > 0 then
            if not string.find(obj.name, "TurretShrine") and obj.team ~= player.team then
                table.insert(towers, obj)
                print("Found a tower")
            end    
       end
    end
end          
        
 			
local function InTurretRange(v)
    for i, tower in ipairs(towers) do
        if tower.health > 0 then
            if GetDistance(v, tower) < 975 then
            	return true
            end
        else
            table.remove(towers, i)
            print("removed a tower")
        end
    end
    return false
end	
				   

function Plugin:__init()
	PrintChat("<font color='#b4005b'>>> The Wonderful Draven Loaded <<</font>")
	AutoCarry.Plugins:RegisterPreAttack(PreAttack)
	AutoCarry.Skills:DisableAll() -- Disable the default SAC skills
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 100, "Spinning Axe", AutoCarry.SPELL_SELF, 0, false, false) 
    SkillW = AutoCarry.Skills:NewSkill(false, _W, 100, "Blood Rush", AutoCarry.SPELL_SELF, 0, false, false) 
    SkillE = AutoCarry.Skills:NewSkill(false, _E, 1050, "Stand Aside", AutoCarry.SPELL_LINEAR_COL, 0, false, false, 1.80, 250, 120, false)
    SkillR = AutoCarry.Skills:NewSkill(false, _R, 5000, "Whirling Death", AutoCarry.SPELL_LINEAR_COL, 0, false, false, 1.80, 250, 120, false)
end

--[[ Move our hero ]]
function Move(pos)
	local moveSqr = math.sqrt((pos.x - myHero.x)^2+(pos.z - myHero.z)^2)
	local moveX = myHero.x + 200*((pos.x - myHero.x)/moveSqr)
	local moveZ = myHero.z + 200*((pos.z - myHero.z)/moveSqr)
	myHero:MoveTo(moveX, moveZ)
end

--[[ Detect when we cast Q ]]
function Plugin:OnProcessSpell(unit, spell)
	if unit.isMe and spell.name == "dravenspinning" then
			qStacks = qStacks + 1
	end
end

--[[ Detect when we catch an axe or when a reticle appears on the floor ]]							   
function Plugin:OnCreateObj(obj)
	if obj.name == "Draven_Q_buf.troy" then
			qBuff = qBuff + 1
	end
   
	if obj ~= nil and obj.name ~= nil and obj.x ~= nil and obj.z ~= nil then
		if obj.name == "Draven_Q_reticle_self.troy" then
			table.insert(reticles, {object = obj, created = GetTickCount()})
		elseif obj.name == "draven_spinning_buff_end_sound.troy" then
			qStacks = 0
		end
	end
end
	
--[[ Detect when an axe is deleted and remove from our list]]	   
--[[ Or detect when we lose a stack of Q buff ]]
function Plugin:OnDeleteObj(obj)
	if obj.name == "Draven_Q_reticle_self.troy" then
		if GetDistance(obj) > qRad then
			qStacks = qStacks - 1
		end
		for i, reticle in ipairs(reticles) do
			if obj and obj.valid and reticle.object and reticle.object.valid and obj.x == reticle.object.x and obj.z == reticle.object.z then
					table.remove(reticles, i)
			end
		end
	elseif obj.name == "Draven_Q_buf.troy" then
		qBuff = qBuff - 1                      
	end
end
		 
--[[ Check if the user has chosen to catch axes in menu ]]
function axesActive()
	return true
end
		
--[[ Cast Q before we attack an enemy ]]	   
function PreAttack(enemy)
	if enemy.dead or not enemy.valid or disableAttacks then return end
	if axesActive() and GetDistance(mousePos) <= DravenConfig.CatchRange then
			if qStacks < 2 then CastSpell(_Q) end
	end
end
	
--[[ Move our hero ]]	  
function doMovement()
	AutoCarry.MyHero:MovementEnabled(false)
	disableMovement = true
	disableAttacks = true
	if GetDistance(player, reticles[1]) > qRadius and IsZoneSafe(reticles[1], reticleSafeZone) and not InTurretRange(reticles[1]) then
	if myHero.canMove then Move({x = closestReticle.object.x, z = closestReticle.object.z}) end
	end
end

function Plugin:OnTick()
	Target = AutoCarry.Crosshair:GetTarget() --- call this every 1 ms with on tick to get our target
	if myHero.dead then return end
	if (AutoCarry.Keys.AutoCarry or AutoCarry.Keys.MixedMode) and DravenConfig.AutoW and ValidTarget(AutoCarry.Orbwalker.target) and not TargetHaveBuff("dravenfurybuff" , myHero) then
		CastSpell(_W)
	end

	--[[ Find the closest axe to us ]]
	for _, particle in pairs(reticles) do
		if closestReticle and closestReticle.object.valid and particle.object and particle.object.valid then
			if GetDistance(particle.object) > GetDistance(closestReticle.object) then
					closestReticle = particle
			end
		else
			closestReticle = particle
		end
	end    

	--[[ Stop if our mouse is in the hold zone ]]
	if GetDistance(mousePos) <= DravenConfig.HoldRange and axesActive() then
		if not stopped then
			myHero:HoldPosition()
			stopped = true
		end
		AutoCarry.MyHero:MovementEnabled(false)
		disableMovement = true
	else
		stopped = false
	end
   
    --[[ Check if there are axes to catch ]]
	if axesActive() and closestReticle and closestReticle.object and closestReticle.object.valid then 
		if GetDistance(mousePos) <= DravenConfig.CatchRange and ((AutoCarry.MainMenu.AutoCarry and ShouldCatch(closestReticle.object)) or (not AutoCarry.MainMenu.AutoCarry)) then
			if GetDistance(closestReticle.object) > qRad then
				doMovement()
			else
				AutoCarry.MyHero:MovementEnabled(false)
				disableMovement = true
				disableAttacks = false
			end
		else
			AutoCarry.MyHero:MovementEnabled(true)
			disableMovement = false
			disableAttacks = false
		end
	elseif GetDistance(mousePos) <= DravenConfig.HoldRange then
		AutoCarry.MyHero:MovementEnabled(false)
		disableMovement = true
		disableAttacks = false
	else
		AutoCarry.MyHero:MovementEnabled(true)
		disableMovement = false
		disableAttacks = false
	end
end
	
--[[ Check if it's safe to catch the axe ]]		-- needs work	   
function ShouldCatch(reticle)
	local enemy
	if AutoCarry.Orbwalker.target ~= nil then enemy = AutoCarry.Orbwalker.target
	elseif AutoCarry.SkillsCrosshair.target ~= nil then enemy = AutoCarry.SkillsCrosshair.target
	else return true end
	if not reticle then return false end
	if GetDistance(mousePos, enemy) > GetDistance(enemy) then
		if GetDistance(reticle, enemy) < GetDistance(enemy) then						
			return false
		end 
		return true
	else
		local closestEnemy
		for _, thisEnemy in pairs(AutoCarry.EnemyTable) do 
			if not closestEnemy then closestEnemy = thisEnemy 
			elseif GetDistance(thisEnemy) < GetDistance(closestEnemy) then closestEnemy = thisEnemy end 
		end
		if closestEnemy then
			local predPos = getPrediction(1.9, 100, closestEnemy)
			if not predPos then return true end
			if GetDistance(reticle, predPos) > getTrueRange() + getHitBoxRadius(closestEnemy) then
					return false
			end
			return true
		else
			return true
		end
	end
end
	
--[[ Calculate last hit damage from Q ]]  -- needs some work
function BonusLastHitDamage(minion)
	if myHero:GetSpellData(_Q).level > 0 and qBuff > 0 then
		return ((myHero.damage + myHero.addDamage) * (0.35 + (0.1 * myHero:GetSpellData(_Q).level)))
	end
	return 0
end


--[[ Drawing stuff ]]		-- why no colours bro -.-   
function Plugin:OnDraw()
	DrawCircle(myHero.x, myHero.y, myHero.z, DravenConfig.HoldRange, 0xFFFFFF)
	DrawCircle(myHero.x, myHero.y, myHero.z, DravenConfig.HoldRange-1, 0xFFFFFF)
	DrawCircle(myHero.x, myHero.y, myHero.z, DravenConfig.CatchRange-1, 0x19A712)
	if axesActive() and DravenConfig.Reminder then
		if GetDistance(mousePos) <= DravenConfig.HoldRange then
			DrawText("Holding Position & Catching",16,100, 100, 0xFF00FF00)
		elseif GetDistance(mousePos) <= DravenConfig.CatchRange then
			DrawText("Orbwalking & Catching",16,100, 100, 0xFF00FF00)
		else	
			DrawText("Only Orbwalking",16,100, 100, 0xFF00FF00)
		end
	end
end

	 
 DravenConfig = AutoCarry.Plugins:RegisterPlugin(Plugin(), "<Draven by Nyan")    

DravenConfig:addParam("HoldRange", "Still Range", SCRIPT_PARAM_SLICE, 130, 0, 450, 0)
DravenConfig:addParam("CatchRange", "Catch Axe Range", SCRIPT_PARAM_SLICE, 575, 0, 2000, 0)
DravenConfig:addParam("AutoW", "Keep W Buff Active Against Enemy", SCRIPT_PARAM_ONOFF, true)
DravenConfig:addParam("AutoCarry", "Use / Catch Axes: Auto Carry Mode", SCRIPT_PARAM_ONOFF, true)
DravenConfig:addParam("LastHit", "Use / Catch Axes: Last Hit Mode", SCRIPT_PARAM_ONOFF, true)
DravenConfig:addParam("LaneClear", "Use / Catch Axes: Lane Clear Mode", SCRIPT_PARAM_ONOFF, true)
DravenConfig:addParam("MixedMode", "Use / Catch Axes: Mixed Mode Mode", SCRIPT_PARAM_ONOFF, true)
DravenConfig:addParam("Reminder", "Display Reminder Text", SCRIPT_PARAM_ONOFF, true)

