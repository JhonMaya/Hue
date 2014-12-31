<?php exit() ?>--by Manciuszz 78.62.151.40
if myHero.charName ~= "Shaco" then return end

local ts, ShacoConfig, enemyTable, HitBoxSize, wayPointManager, posAcquired
local fetchedTick = 0
local myHeroPos, createdObj = {}, {}

local LastAttack, LastWindUp, LastAttackCD, AttackCompletesAt = GetTickCount(), 1440*(2.921503831 / myHero.attackSpeed), 1000/(myHero.attackSpeed*0.694), GetTickCount()

local Stage = 0
_SHOOT = 0
_MOVE = 1
_SHOOTING = 2

function getHitBox(hero)
    local heroName = (hero and type(hero) == userdata and hero.charName) or hero
    local hitboxTable = { ['HeimerTGreen'] = 50.0, ['Darius'] = 80.0, ['ZyraGraspingPlant'] = 20.0, ['HeimerTRed'] = 50.0, ['ZyraThornPlant'] = 20.0, ['Nasus'] = 80.0, ['HeimerTBlue'] = 50.0, ['SightWard'] = 1, ['HeimerTYellow'] = 50.0, ['Kennen'] = 55.0, ['VisionWard'] = 1, ['ShacoBox'] = 10, ['HA_AP_Poro'] = 0, ['TempMovableChar'] = 48.0, ['TeemoMushroom'] = 50.0, ['OlafAxe'] = 50.0, ['OdinCenterRelic'] = 48.0, ['Blue_Minion_Healer'] = 48.0, ['AncientGolem'] = 100.0, ['AnnieTibbers'] = 80.0, ['OdinMinionGraveyardPortal'] = 1.0, ['OriannaBall'] = 48.0, ['LizardElder'] = 65.0, ['YoungLizard'] = 50.0, ['OdinMinionSpawnPortal'] = 1.0, ['MaokaiSproutling'] = 48.0, ['FizzShark'] = 0, ['Sejuani'] = 80.0, ['Sion'] = 80.0, ['OdinQuestIndicator'] = 1.0, ['Zac'] = 80.0, ['Red_Minion_Wizard'] = 48.0, ['DrMundo'] = 80.0, ['Blue_Minion_Wizard'] = 48.0, ['ShyvanaDragon'] = 80.0, ['HA_AP_OrderShrineTurret'] = 88.4, ['Heimerdinger'] = 55.0, ['Rumble'] = 80.0, ['Ziggs'] = 55.0, ['HA_AP_OrderTurret3'] = 88.4, ['HA_AP_OrderTurret2'] = 88.4, ['TT_Relic'] = 0, ['Veigar'] = 55.0, ['HA_AP_HealthRelic'] = 0, ['Teemo'] = 55.0, ['Amumu'] = 55.0, ['HA_AP_ChaosTurretShrine'] = 88.4, ['HA_AP_ChaosTurret'] = 88.4, ['HA_AP_ChaosTurretRubble'] = 88.4, ['Poppy'] = 55.0, ['Tristana'] = 55.0, ['HA_AP_PoroSpawner'] = 50.0, ['TT_NGolem'] = 80.0, ['HA_AP_ChaosTurretTutorial'] = 88.4, ['Volibear'] = 80.0, ['HA_AP_OrderTurretTutorial'] = 88.4, ['TT_NGolem2'] = 80.0, ['HA_AP_ChaosTurret3'] = 88.4, ['HA_AP_ChaosTurret2'] = 88.4, ['Shyvana'] = 50.0, ['HA_AP_OrderTurret'] = 88.4, ['Nautilus'] = 80.0, ['ARAMOrderTurretNexus'] = 88.4, ['TT_ChaosTurret2'] = 88.4, ['TT_ChaosTurret3'] = 88.4, ['TT_ChaosTurret1'] = 88.4, ['ChaosTurretGiant'] = 88.4, ['ARAMOrderTurretFront'] = 88.4, ['ChaosTurretWorm'] = 88.4, ['OdinChaosTurretShrine'] = 88.4, ['ChaosTurretNormal'] = 88.4, ['OrderTurretNormal2'] = 88.4, ['OdinOrderTurretShrine'] = 88.4, ['OrderTurretDragon'] = 88.4, ['OrderTurretNormal'] = 88.4, ['ARAMChaosTurretFront'] = 88.4, ['ARAMOrderTurretInhib'] = 88.4, ['ChaosTurretWorm2'] = 88.4, ['TT_OrderTurret1'] = 88.4, ['TT_OrderTurret2'] = 88.4, ['ARAMChaosTurretInhib'] = 88.4, ['TT_OrderTurret3'] = 88.4, ['ARAMChaosTurretNexus'] = 88.4, ['OrderTurretAngel'] = 88.4, ['Mordekaiser'] = 80.0, ['TT_Buffplat_R'] = 0, ['Lizard'] = 50.0, ['GolemOdin'] = 80.0, ['Renekton'] = 80.0, ['Maokai'] = 80.0, ['LuluLadybug'] = 50.0, ['Alistar'] = 80.0, ['Urgot'] = 80.0, ['LuluCupcake'] = 50.0, ['Gragas'] = 80.0, ['Skarner'] = 80.0, ['Yorick'] = 80.0, ['MalzaharVoidling'] = 10.0, ['LuluPig'] = 50.0, ['Blitzcrank'] = 80.0, ['Chogath'] = 80.0, ['Vi'] = 50, ['FizzBait'] = 0, ['Malphite'] = 80.0, ['EliseSpiderling'] = 1.0, ['Dragon'] = 100.0, ['LuluSquill'] = 50.0, ['Worm'] = 100.0, ['redDragon'] = 100.0, ['LuluKitty'] = 50.0, ['Galio'] = 80.0, ['Annie'] = 55.0, ['EliseSpider'] = 50.0, ['SyndraSphere'] = 48.0, ['LuluDragon'] = 50.0, ['Hecarim'] = 80.0, ['TT_Spiderboss'] = 200.0, ['Thresh'] = 55.0, ['ARAMChaosTurretShrine'] = 88.4, ['ARAMOrderTurretShrine'] = 88.4, ['Blue_Minion_MechMelee'] = 65.0, ['TT_NWolf'] = 65.0, ['Tutorial_Red_Minion_Wizard'] = 48.0, ['YorickRavenousGhoul'] = 1.0, ['SmallGolem'] = 80.0, ['OdinRedSuperminion'] = 55.0, ['Wraith'] = 50.0, ['Red_Minion_MechCannon'] = 65.0, ['Red_Minion_Melee'] = 48.0, ['OdinBlueSuperminion'] = 55.0, ['TT_NWolf2'] = 50.0, ['Tutorial_Red_Minion_Basic'] = 48.0, ['YorickSpectralGhoul'] = 1.0, ['Wolf'] = 50.0, ['Blue_Minion_MechCannon'] = 65.0, ['Golem'] = 80.0, ['Blue_Minion_Basic'] = 48.0, ['Blue_Minion_Melee'] = 48.0, ['Odin_Blue_Minion_caster'] = 48.0, ['TT_NWraith2'] = 50.0, ['Tutorial_Blue_Minion_Wizard'] = 48.0, ['GiantWolf'] = 65.0, ['Odin_Red_Minion_Caster'] = 48.0, ['Red_Minion_MechMelee'] = 65.0, ['LesserWraith'] = 50.0, ['Red_Minion_Basic'] = 48.0, ['Tutorial_Blue_Minion_Basic'] = 48.0, ['GhostWard'] = 1, ['TT_NWraith'] = 50.0, ['Red_Minion_MechRange'] = 65.0, ['YorickDecayedGhoul'] = 1.0, ['TT_Buffplat_L'] = 0, ['TT_ChaosTurret4'] = 88.4, ['TT_Buffplat_Chain'] = 0, ['TT_OrderTurret4'] = 88.4, ['OrderTurretShrine'] = 88.4, ['ChaosTurretShrine'] = 88.4, ['WriggleLantern'] = 1, ['ChaosTurretTutorial'] = 88.4, ['TwistedLizardElder'] = 65.0, ['RabidWolf'] = 65.0, ['OrderTurretTutorial'] = 88.4, ['OdinShieldRelic'] = 0, ['TwistedGolem'] = 80.0, ['TwistedSmallWolf'] = 50.0, ['TwistedGiantWolf'] = 65.0, ['TwistedTinyWraith'] = 50.0, ['TwistedBlueWraith'] = 50.0, ['TwistedYoungLizard'] = 50.0, ['Summoner_Rider_Order'] = 65.0, ['Summoner_Rider_Chaos'] = 65.0, ['Ghast'] = 60.0, ['blueDragon'] = 100.0, }
    return (hitboxTable[heroName] ~= nil and hitboxTable[heroName] ~= 0 and hitboxTable[heroName]) or 65
end

function OnLoad()
    enemyTable = GetEnemyHeroes()
    HitBoxSize = getHitBox(myHero)
    wayPointManager = WayPointManager()

    ShacoConfig = scriptConfig("Shaco", "Shaco")
	ShacoConfig:addParam("AutoShiv", "AutoShiv if Killable", SCRIPT_PARAM_ONOFF, true)
	ShacoConfig:addParam("CloneOrbwalk", "Auto-Orbwalk Clone", SCRIPT_PARAM_ONOFF, true)
	ShacoConfig:addParam("DeceivingClone", "Juke Mode for Clone", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("H"))
	ShacoConfig:addParam("DeceiveZone", "Deceive Zone Circle", SCRIPT_PARAM_ONOFF, true)
	ShacoConfig:permaShow("AutoShiv")
	ShacoConfig:permaShow("DeceivingClone")

    ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 2000, DAMAGE_PHYSICAL, true)
    ts.name = "Shaco"
    ShacoConfig:addTS(ts)

	print(">> Shaco loaded")
end

function OnProcessSpell(unit, spell)
    if myHero.dead then return end

    if not unit.isMe and myHero.team == unit.team then
        if spell.name:lower():find("shaco") and spell.name ~= "ShacoBoxSpell" then
            --print(">>> Attacked!")

            LastAttack = GetTickCount()
            LastWindUp = spell.windUpTime * 1000
            LastAttackCD = spell.animationTime * 1000
            AttackCompletesAt = LastAttack + LastWindUp
            Stage = _SHOOTING
        end
    end
end

function AutoShiv()
    for i, target in pairs(enemyTable) do
        if target ~= nil and not target.dead and target.visible and GetDistance(target) < 625 then
            local TruDMG = getDmg("E", target, myHero)
            if target.health < TruDMG then
                CastSpell(_E, target)
            end
        end
    end
end

function Orbwalk(clone, target)
    -- IF clone attack is ready and able to attack
    if target ~= nil and Stage == _SHOOT then
        CastSpell(_R, target)
--        print(">> Attacking!")
    elseif Stage == _MOVE or target == nil then -- IF clone can move
        local wayPoint = (target ~= nil and WayPointManager:GetSimulatedWayPoints(target, 1, 1)[1]) or nil -- Get the path enemy is taking.
        local orbwalkTarget = (wayPoint ~= nil and {x = wayPoint.x, y = myHero.y, z = wayPoint.y}) or (target ~= nil and target or mousePos) -- If that path is available then return the coordinates else orbwalk to mouse position to prevent clone from staying in one spot
        local moveToPos = clone + (Vector(orbwalkTarget) - clone):normalized()*(GetDistance(clone, target)+200) -- click in front of the clone by 100 units towards the path
        CastSpell(_R, moveToPos.x, moveToPos.z)
--        print("> Walking!")
    end
end

function OnTick()
	ts:update()
	if myHero.dead then return end
	
	if ShacoConfig.AutoShiv then AutoShiv() end

    if ShacoConfig.CloneOrbwalk then
        if createdObj.clone ~= nil then

            -- Orbwalking helper
            if GetTickCount() > (LastAttack + LastAttackCD) then
                Stage = _SHOOT
            elseif GetTickCount() > AttackCompletesAt then
                Stage = _MOVE
            end

            --Assign a target
            local priorityTarget = (ts.target ~= nil and ts.target) or GetTarget()
            if not ShacoConfig.DeceivingClone then -- If juke mode IS NOT selected
                Orbwalk(createdObj.clone, priorityTarget) --Orbwalk the target
            else
                -- If target was assigned then choose a random direction and force clone to run there, else force clone to follow myHero movement(mouse position).
                local enemyVector = (priorityTarget ~= nil and ((2*(math.random(0,1)-0.5)) > 0 and Vector(priorityTarget):perpendicular2()) or Vector(priorityTarget):perpendicular()) or mousePos
                if enemyVector then
                    local moveToPos = createdObj.clone + (enemyVector - createdObj.clone):normalized()*(GetDistance(createdObj.clone, enemyVector) - 300)
                    CastSpell(_R, moveToPos.x, moveToPos.z)
                end
            end

        end
    end
end

function OnCreateObj(object)
	if object ~= nil and object.name:find("Jester_Copy") then
        createdObj = {
            clone = object,
            lastCPosUpdate = GetTickCount(),
        }
	end
end

function OnDeleteObj(object)
	if object ~= nil and object.name:find("Jester_Copy") then
        createdObj = {
            clone = nil,
            lastCPosUpdate = GetTickCount(),
        }
    end
end

function OnDraw()
	if not myHero.dead and ShacoConfig.DeceiveZone then

        --Moveable distance while using Q spell.
        if myHero:GetSpellData(_Q).level > 0 then
            if myHero:CanUseSpell(_Q) == SUPRESSED then
                if not posAcquired then
                    myHeroPos = { x = myHero.x, y = myHero.y, z = myHero.z }
                    fetchedTick = GetTickCount()
                    posAcquired = true
                end
            else
                if posAcquired or (GetTickCount() > (fetchedTick + 3500)) then
                    posAcquired = false
                end
            end

            if myHero:CanUseSpell(_Q) == READY or myHero:CanUseSpell(_Q) == SUPRESSED then
                local dist = 500 + myHero.ms*3.5
                if posAcquired then
                    DrawCircle(myHeroPos.x, myHeroPos.y, myHeroPos.z, dist, ARGB(255, 0, 255, 0))
                else
                    DrawCircle(myHero.x, myHero.y, myHero.z, dist, ARGB(255, 0, 255, 0))
                end
            end
        end

        -- E range indicator
		DrawCircle(myHero.x, myHero.y, myHero.z, 625, 0x19A712)

        -- Focused target indicator
        if ValidTarget(ts.target) then DrawCircle(ts.target.x, ts.target.y, ts.target.z, 100, 0x00FF000) end

        -- Clone expiration time indicator
		if createdObj.clone ~= nil then
			local Time = tostring(math.round((18000-(GetTickCount() - createdObj.lastCPosUpdate))/1000,0))
			local objectX, objectY, onScreen = get2DFrom3D(createdObj.clone.x, createdObj.clone.y, createdObj.clone.z)
			DrawText(Time, 15, objectX, objectY-100, 0xFF00FF00)
        end

    end

end