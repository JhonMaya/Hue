<?php exit() ?>--by Manciuszz 78.62.151.40
if _G.M123WVZ_Y4MZY_U5_5QU_KDAR_HG123 ~= nil then
    local accessCodes = { --Code pattern: XXXX-XXXX-X-XX-XXXX-XX <- http://www.randomcodegenerator.com/generate-codes
        'NHFK-BZRF-L-CP-KFLL-QH', --Manciuszz
        'XFKU-QCYA-E-BQ-ECFV-SG', --Fred (frd)
    }
    if _G.M123WVZ_Y4MZY_U5_5QU_KDAR_HG123[GetUser():lower()][1] then
        local accessGranted = false
        for i=1, #accessCodes do
            if _G.M123WVZ_Y4MZY_U5_5QU_KDAR_HG123[GetUser():lower()][2] == accessCodes[i] then
                accessGranted = true
                break
            end
        end
        if not accessGranted then
            print("ACCESS DENIED -> No permissions to run the script!")
            return
        end
    else
        print("ACCESS DENIED -> No permissions to run the script!")
        return
    end
else
    print("ACCESS DENIED -> No permissions to run the script!")
    return
end

require "SpellDatabase"
local target, OrbConfig, ts, aaRange
local championData, colors = {}, { red = ARGB(255,255,0,0), green = ARGB(255,0,255,0)}
local HitBox = 65*1.9
local enemyTable = GetEnemyHeroes()
local lastAttack = GetTickCount()
local spellNameTable = {
    demResetSpell = {
        ['VayneTumble'] = true, ["Powerfist"] = true, ["DariusNoxianTacticsONH"] = true, ["Takedown"] = true,
        ["SivirW"] = true, ["BlindingDart"] = true, ["JaxEmpowerTwo"] = true, ["MordekaiserMaceOfSpades"] = true,
        ["SiphoningStrikeNew"] = true,  ["RengarQ"] = true, ["MonkeyKingDoubleAttack"] = true, ["YorickSpectral"] = true, ["ViE"] = true,
        ["GarenSlash3"] = true, ["HecarimRamp"] = true, ["XenZhaoComboTarget"] = true, ["LeonaShieldOfDaybreak"] = true, ["ShyvanaDoubleAttack"] = true,
        ["TalonNoxianDiplomacy"] = true, ["TrundleTrollSmash"] = true, ["VolibearQ"] = true, ["PoppyDevastatingBlow"] = true
    },

    demNoneAttacks = {
        ["shyvanadoubleattackdragon"] = true, ["ShyvanaDoubleAttack"] = true,
        ["MonkeyKingDoubleAttack"] = true, ["JarvanIVCataclysmAttack"] = true,
    },

    demSpellAttacks = {
        ["frostarrow"] = true, ["CaitlynHeadshotMissile"] = true, ["QuinnWEnhanced"] = true, ["TrundleQ"] = true,
        ["XenZhaoThrust"] = true, ["XenZhaoThrust2"] = true, ["XenZhaoThrust3"] = true,
        ["GarenSlash2"] = true, ["RenektonExecute"] = true, ["RenektonSuperExecute"] = true, ["sonaariaofperseveranceupgrade"] = true,
        ["RengarNewPassiveBuffDash"] = true, ["MasterYiDoubleStrike"] = true, ["KennenMegaProc"] = true,
    },

    resetAttackSpells = {
        MissFortune = _Q, Rengar = _Q, Sivir = _W, Jax = _W,
        MonkeyKing = _Q,  Vayne = _Q, Vi = _E, Lucian = _Q,
    }
}

if VIP_USER then myHero.Attack = function(self, unit) _G.attackedUnit = unit.networkID  Packet('S_MOVE', {targetNetworkId = unit.networkID, type = 3}):send() end end
function OnLoad()
    _LastHit()
    OrbConfig = scriptConfig("Orbing 2013", "Orbing2013")
    OrbConfig:addSubMenu("Last Hitting settings", "lastHitSettings")
    OrbConfig.lastHitSettings:addParam("butcherMastery", "Butcher Mastery", SCRIPT_PARAM_ONOFF, false)
    OrbConfig.lastHitSettings:addParam("spellBladeMastery", "Spellblade Mastery", SCRIPT_PARAM_ONOFF, false)
    OrbConfig.lastHitSettings:addParam("edgedSwordMastery", "Edged-Sword Mastery", SCRIPT_PARAM_ONOFF, false)
    OrbConfig.lastHitSettings:addParam("havocMastery", "Havoc Mastery", SCRIPT_PARAM_ONOFF, false)
    OrbConfig:addParam("BLANKSPACE", " -- General Settings ------------------------", SCRIPT_PARAM_INFO, "")
    OrbConfig:addParam("autoAttackAwareness", "Enable autoAttackAwareness", SCRIPT_PARAM_ONOFF, true)
    OrbConfig:addParam("DrawAARANGE", "Display AA-Range", SCRIPT_PARAM_ONOFF, true)
    if spellsAvailable() then OrbConfig:addParam("targetMode", "Use ResetSpell: All/Hero/Minion", SCRIPT_PARAM_SLICE, 0,0,3,0) end
    OrbConfig:addParam("EnableTargetSelector", "Enable Automatic TargetSelecting", SCRIPT_PARAM_ONOFF, true)
    OrbConfig:addParam("priorty2Selected", "Priority to selected target", SCRIPT_PARAM_ONOFF, true)
    OrbConfig:addParam("scriptActive", "Orbwalk OnHold", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("A"))
    OrbConfig:addParam("lastHitting", "LastHit OnHold",  SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
    OrbConfig:addParam("laneClear", "LaneClear OnHold",  SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))

    print("Pimpin ain't easy -Doublelift 2013")
end

--  Last-Hitting ---------------------------

class '_LastHit' local Minions = nil
function _LastHit:__init()
    self.IncomingDamage = {}
    self.lastUpdateTime = GetTickCount()
    self.updatePacketHeader = 0xC4
    self.ScanRange = 2000
    self.heroProjSpeed = self:GetProjectileSpeed(myHero, {name = (myHero.charName == "Jayce" and 'JayceRangedAttack' or myHero.charName..'BasicAttack')})
    self.EnemyMinions = minionManager(MINION_ENEMY, self.ScanRange, myHero, MINION_SORT_HEALTH_ASC)
    Minions = self

    self.PacketSupport = true
    AddProcessSpellCallback(function(unit, spell) self:_OnProcessSpell(unit, spell) end)
    if VIP_USER then AddRecvPacketCallback(function(p) self:_OnRecvPacket(p) end) else self.PacketSupport = false end
--    AddDrawCallback(function() self:_OnDraw() end)
    AddTickCallback(function() self:_OnTick() end)
end

function _LastHit:_OnRecvPacket(p)
    if p.header == self.updatePacketHeader then self.lastUpdateTime = GetTickCount() - GetLatency()/2 end
    if p.header == 0x6D then
        p.pos = 1
        local sourceNetworkId = p:DecodeF()
        if sourceNetworkId == myHero.networkID then
            championData.MissileLaunched = true
        end
    end
end
function _LastHit:CalcDamageOfAttack(source, target, spell, additionalDamage)
    -- read initial armor and damage values
    local armorPenPercent = source.armorPenPercent
    local armorPen = source.armorPen
    local totalDamage = source.totalDamage + (additionalDamage or 0)
    local damageMultiplier = spell.name:find("CritAttack") and 2 or 1

    -- minions give wrong values for armorPen and armorPenPercent
    if source.type == "obj_AI_Minion" then
        armorPenPercent = 1
    elseif source.type == "obj_AI_Turret" then
        armorPenPercent = 0.7
    end

    -- turrets ignore armor penetration and critical attacks
    if target.type == "obj_AI_Turret" then
        armorPenPercent = 1
        armorPen = 0
        damageMultiplier = 1
    end

    -- calculate initial damage multiplier for negative and positive armor

    local targetArmor = (target.armor * armorPenPercent) - armorPen
    if targetArmor < 0 then -- minions can't go below 0 armor.
        --damageMultiplier = (2 - 100 / (100 - targetArmor)) * damageMultiplier
        damageMultiplier = 1 * damageMultiplier
    else
        damageMultiplier = 100 / (100 + targetArmor) * damageMultiplier
    end

    -- use ability power or ad based damage on turrets
    if source.type == "obj_AI_Hero" and target.type == "obj_AI_Turret" then
        totalDamage = math.max(source.totalDamage, source.damage + 0.4 * source.ap)
    end

    -- minions deal less damage to enemy heros
    if source.type == "obj_AI_Minion" and target.type == "obj_AI_Hero" and source.team ~= TEAM_NEUTRAL then
        damageMultiplier = 0.60 * damageMultiplier
    end

    -- heros deal less damage to turrets
    if source.type == "obj_AI_Hero" and target.type == "obj_AI_Turret" then
        damageMultiplier = 0.95 * damageMultiplier
    end

    -- minions deal less damage to turrets
    if source.type == "obj_AI_Minion" and target.type == "obj_AI_Turret" then
        damageMultiplier = 0.475 * damageMultiplier
    end

    -- siege minions and superminions take less damage from turrets
    if source.type == "obj_AI_Turret" and (target.charName == "Red_Minion_MechCannon" or target.charName == "Blue_Minion_MechCannon") then
        damageMultiplier = 0.8 * damageMultiplier
    end

    -- caster minions and basic minions take more damage from turrets
    if source.type == "obj_AI_Turret" and (target.charName == "Red_Minion_Wizard" or target.charName == "Blue_Minion_Wizard" or target.charName == "Red_Minion_Basic" or target.charName == "Blue_Minion_Basic") then
        damageMultiplier = (1 / 0.875) * damageMultiplier
    end

    -- turrets deal more damage to all units by default
    if source.type == "obj_AI_Turret" then
        damageMultiplier = 1.05 * damageMultiplier
    end

    -- turret damage scales for champions
    if source.type == "obj_AI_Turret" and target.type == "obj_AI_Hero" then
        if self.turretStacks[source.networkID] and self.turretStacks[source.networkID].lastTarget == target.networkID and (GetTickCount() - self.turretStacks[source.networkID].lastAttack) < 4000 then
            damageMultiplier = (1 + (0.25 * math.min(self.turretStacks[source.networkID].attackCount, 5))) * damageMultiplier

            self.turretStacks[source.networkID].attackCount = self.turretStacks[source.networkID].attackCount + 1
            self.turretStacks[source.networkID].lastAttack = GetTickCount()
        elseif self.turretStacks[source.networkID] and self.turretStacks[source.networkID].lastTargetType == "obj_AI_Hero" and (GetTickCount() - self.turretStacks[source.networkID].lastAttack) < 4000 then
            self.turretStacks[source.networkID].attackCount = math.min(self.turretStacks[source.networkID].attackCount, 3)

            damageMultiplier = (1 + (0.25 * self.turretStacks[source.networkID].attackCount)) * damageMultiplier

            self.turretStacks[source.networkID].lastTarget = target
            self.turretStacks[source.networkID].attackCount = self.turretStacks[source.networkID].attackCount + 1
            self.turretStacks[source.networkID].lastAttack = GetTickCount()
        else
            self.turretStacks[source.networkID] = {
                lastTarget = target.networkID,
                lastTargetType = target.type,
                lastAttack = GetTickCount(),
                attackCount = 1
            }
        end
    end

    -- calculate damage dealt
    return damageMultiplier * totalDamage
end

function _LastHit:GetProjectileSpeed(unit, spell)
    local MissileSpeed = (SpellDatabase[spell.name] ~= nil and (SpellDatabase[spell.name]['SpellData:MissileSpeed'] or SpellDatabase[spell.name]['SpellData:MissileMaxSpeed'])) or 0
    return (unit.isRanged and 0) or MissileSpeed/1000
end

function _LastHit:getAdditionalDamage() --This function main purpose is to add the additional damage into last-hitting calculations from auto attacks that is buffed by spells,passives etc..
    local additionalDamage = {
        Teemo       = myHero:GetSpellData(_E).level > 0 and ((GetSpellData(_E).level * 10) + (myHero.ap * 0.3)) or 0,
        Vayne       = myHero:GetSpellData(_Q).level > 0 and myHero:CanUseSpell(_Q) == SUPRESSED and (((0.05*myHero:GetSpellData(_Q).level) + 0.25 )*myHero.totalDamage) or 0,
        Varus       = myHero:GetSpellData(_W).level > 0 and (math.max((4*myHero:GetSpellData(_W).level + 6 + 0.25*myHero.ap))) or 0,
        Corki       = myHero.totalDamage/10,
--        Caitlyn     = caitlynPassive and myHero.totalDamage * 1.5 or 0,
--        Rengar      = myHero:GetSpellData(_Q).level > 0 and myHero:CanUseSpell(_Q) == SUPRESSED and (rengarStacks == 5 and (30*myHero:GetSpellData(_Q).level)+myHero.totalDamage or (30*myHero:GetSpellData(_Q).level)) or 0 ,
        MissFortune = myHero:GetSpellData(_W).level > 0 and ((2+2*myHero:GetSpellData(_W).level) + (myHero.ap*0.05)) or 0,
--        Sivir       = myHero:GetSpellData(_W).level > 0 and myHero:CanUseSpell(_W) == SUPRESSED and (5+(15*myHero:GetSpellData(_W).level)) or 0,
        Orianna     = (8*(((myHero.level - 1) / 3) + 1) + 2 + 0.15*myHero.ap),
--        Draven      = myHero:GetSpellData(_Q).level > 0 and qBuff > 0 and (myHero.totalDamage*(0.35 + (0.1 * myHero:GetSpellData(_Q).level))) or 0,
--        Kayle       = kayleBuff and (10 + (10*myHero:GetSpellData(_E).level) + (0.4*myHero.ap)) or 0,
--        Jayce       = jayceWcasted and ((myHero.totalDamage*(15*myHero:GetSpellData(_W).level+55)/100) - myHero.totalDamage) or 0,
--        Lucian      = LucianPassive and myHero.totalDamage+(myHero.totalDamage/2) or 0
    }
    return additionalDamage[myHero.charName] or 0
end

function _LastHit:GetSecondLowestHealthMinion()
    local found = nil
    for i=1, #self.EnemyMinions.objects do
        local Minion = self.EnemyMinions.objects[i]
        if ValidTarget(Minion, aaRange) then
            if Minion.health < Minion.maxHealth/2 then

            else
                if found then return Minion
                else found = Minion
                end
            end
        end
    end
    return found
end

function _LastHit:GetDamageOnUnit(Target, LastHit)
    local MyDamage = self:CalcDamageOfAttack(myHero, Target, {name = 'BasicAttack'}, self:getAdditionalDamage() )
    if LastHit then MyDamage = MyDamage + self:GetBotrkBonusLastHitDamage(Target) + self:GetMasteryAdditionalLastHitDamage(MyDamage, Target) end
    return MyDamage
end

function _LastHit:GetBotrkBonusLastHitDamage(Target)
    if GetInventoryHaveItem(3153) then
        if ValidTarget(Target, aaRange) then
            local BladePassiveDamage = myHero:CalcDamage(Target, Target.health*0.05) --5% of currentHP
            BladePassiveDamage = BladePassiveDamage >= 60 and 60 or BladePassiveDamage
            return BladePassiveDamage
        end
    end
    return 0
end

function _LastHit:GetMasteryAdditionalLastHitDamage(Damage, Target)
    local _Damage = Damage
    _Damage = (OrbConfig.lastHitSettings.edgedSwordMastery and _Damage + (_Damage * (myHero.isRanged and 0.02 or 0.015))) or _Damage
    _Damage = (OrbConfig.lastHitSettings.spellBladeMastery and _Damage + myHero:CalcMagicDamage(Target, myHero.ap*0.05)) or _Damage
    _Damage = (OrbConfig.lastHitSettings.havocMastery      and _Damage + (_Damage * 0.03)) or _Damage
    _Damage = (OrbConfig.lastHitSettings.butcherMastery    and _Damage + 2) or _Damage
    return _Damage - Damage
end

class 'DataExtraction'
function DataExtraction:__init(source, spell)
    self.Source  = source
    self.Target  = spell.target
    self.Started = GetTickCount()
    self.Delay   = spell.windUpTime*1000
    self.Speed   = Minions:GetProjectileSpeed(self.Source, spell)
    self.Damage  = Minions:CalcDamageOfAttack(self.Source, self.Target, spell)
    self.LandsAt = self.Started + self.Delay + (not self.Source.isRanged and self.Speed > 0 and GetDistance(self.Source, self.Target)/self.Speed or 0) + (self.PacketSupport and self.Source.type ~= myHero.type and GetTickCount() < Minions.lastUpdateTime+125 and (GetTickCount() - Minions.lastUpdateTime) or (self.Source.type ~= myHero.type and GetLatency() or -GetLatency()))
end

function _LastHit:_OnProcessSpell(unit, spell)
    if unit and spell.target and unit.team == myHero.team and not unit.isMe and GetDistance(unit) <= self.ScanRange then
        if spell.target.team ~= myHero.team and spell.target.type == "obj_AI_Minion" and (spell.name:lower():find("attack") or spell.name == unit:GetSpellData(_Q).name or spell.name == unit:GetSpellData(_W).name or spell.name == unit:GetSpellData(_E).name or spell.name == unit:GetSpellData(_R).name)  then
            self.IncomingDamage[unit.networkID] = DataExtraction(unit, spell)
        end
    end
end

function _LastHit:GetAutoAttackArrivalTime(Target)
    local myAutoAttackProjectileTravelTime = (not myHero.isRanged and GetDistance(Target)/self.heroProjSpeed) or (championData.BaseWindUpTime ~= nil and championData.BaseWindUpTime/myHero.attackSpeed or 300)
    --NOTE: if self:GetAutoAttackArrivalTime(Attack.Target) > Attack.LandsAt then
    return GetTickCount() + myAutoAttackProjectileTravelTime - GetLatency()/2
end

function _LastHit:tidyIncomingDamage()
    for netID, attack in pairs(self.IncomingDamage) do
        if attack ~= nil and attack.Source ~= nil and attack.Target ~= nil and attack.Source.valid and attack.Target.valid and not attack.Source.dead and not attack.Target.dead then
            if GetTickCount() > attack.LandsAt then
                self.IncomingDamage[netID] = nil
            end
        else
            self.IncomingDamage[netID] = nil
        end
    end
end

function _LastHit:GetPredictedDamage(Attack)
    if self:GetAutoAttackArrivalTime(Attack.Target) > Attack.LandsAt then
        return Attack.Damage, Attack.Damage
    end
    return 0, Attack.Damage
end

function _LastHit:GetKillableMinion()
    for _, EnemyMinion in pairs(self.EnemyMinions.objects) do
        local Damage, TotalDamage = 0, 0

        if ValidTarget(EnemyMinion, self.ScanRange) then
            if EnemyMinion.health < self:GetDamageOnUnit(EnemyMinion, true) then
                return EnemyMinion, nil
            else
                for _, Attack in pairs(self.IncomingDamage) do
                    if Attack.Target.networkID == EnemyMinion.networkID then
                        local _Damage, _TotalDamage = self:GetPredictedDamage(Attack)
                        Damage, TotalDamage = Damage + _Damage, TotalDamage + _TotalDamage
                    end
                end

                if (EnemyMinion.health - Damage) < self:GetDamageOnUnit(EnemyMinion, true) then
                    return EnemyMinion, nil
                elseif (EnemyMinion.health - TotalDamage) < self:GetDamageOnUnit(EnemyMinion, true) then
                    return nil, EnemyMinion
                end
            end
        end
    end
end

function _LastHit:_OnDraw()
    DrawDebugText("Last Update Tick: "..GetTickCount())
    for l, attack in pairs(Minions.IncomingDamage) do
        if attack then
            if attack.Speed ~= 0 and GetTickCount() < attack.LandsAt + GetLatency() then
                DrawLines3D({attack.Source, attack.Target}, 1, colors.red)
                local attackDifference = GetTickCount() - (attack.Started + attack.Delay)
                local distancerec = attackDifference * attack.Speed
                local poscircle = attack.Source + (Vector(attack.Target) - attack.Source):normalized()*distancerec
                DrawLines3D({poscircle, attack.Target}, 2, colors.green)
            end
        end
    end
end

function _LastHit:_OnTick()
    self:tidyIncomingDamage()
    self.EnemyMinions:update()

    if not ValidTarget(self.KillableMinion, self.ScanRange) then
        local Killable, NearDeath = self:GetKillableMinion()
        if ValidTarget(Killable, aaRange) then
            self.KillableMinion = Killable
        elseif ValidTarget(NearDeath, aaRange) then
            self.NearDeath = NearDeath
        else
            self.KillableMinion, self.NearDeath = nil, nil
        end
    end
end

--  Core ---------------------------
function OnProcessSpell(unit, spell)
    if myHero.dead then return end

    if unit.isMe then
        if (spellNameTable.demSpellAttacks[spell.name] or spell.name:lower():find('attack')) and not spellNameTable.demNoneAttacks[spell.name] then
            lastAttack = GetTickCount() - GetLatency()/2
            if championData.BaseWindUpTime == nil then
                championData = {
                    BaseAttackSpeed = 1000/(1000*spell.animationTime*myHero.attackSpeed),
                    BaseWindUpTime = 1000*spell.windUpTime*myHero.attackSpeed,
                }
            end
            championData.MissileLaunched = false
        elseif spellNameTable.demResetSpell[spell.name] then
            championData.MissileLaunched = false
            lastAttack = GetTickCount() - (1000*spell.animationTime + GetLatency()/2)
        end
    end
end

function CastItemActives(target)
    if ValidTarget(target, 500, true) and target.type == myHero.type then
        if GetInventoryHaveItem(3153) or GetInventoryHaveItem(3144) or GetInventoryHaveItem(3146) or GetInventoryHaveItem(3074) or GetInventoryItemIsCastable(3077) then
            if GetInventoryItemIsCastable(3153) then --BoTRK
                CastItem(3153, target)
            elseif GetInventoryItemIsCastable(3144) then -- Bilgewater Cutlass
                CastItem(3144, target)
            elseif GetInventoryItemIsCastable(3146) then -- Hextech Gunblade
                CastItem(3146, target)
            elseif GetInventoryItemIsCastable(3074) or GetInventoryItemIsCastable(3077) then -- Ravenous Hydra/Tiamat
                if GetDistance(target) <= 400 then
                    CastItem(3077)
                    CastItem(3074)
                end
            end
        end
    end
end

function bestTarget(range)
    local datTarget, efHP = nil, 0
    if OrbConfig.priorty2Selected and ValidTarget(GetTarget(), range, true) and GetTarget().type == myHero.type then
        return datTarget
    else
        for i, enemy in pairs(enemyTable) do
            if ValidTarget(enemy, range, true) then
                local effectiveHealth = enemy.health*( ( 100 + ( (enemy.armor - ( (enemy.armor*myHero.armorPenPercent)/100 ) ) - myHero.armorPen) )/100 )
                if (datTarget == nil or effectiveHealth < efHP) then
                    datTarget = enemy
                    efHP = effectiveHealth
                end
            end
        end
    end
    return datTarget
end

function spellsAvailable(enemy, type, diff) -- Supports only targetable spells atm.
    if enemy == nil and type == nil and diff == nil then return spellNameTable.resetAttackSpells[myHero.charName] ~= nil end

    local targetType = enemy and ((type == 2 and enemy.type == "obj_AI_Hero") or (type == 3 and enemy.type == "obj_AI_Minion") or (type == 1 and enemy.type))

--    if myHero.charName == "Rengar" and targetType and GetDistance(enemy) < 300 then
--        local spellOrder = AcConfig.swapSpellOrder and {_Q, _W} or {_W, _Q}
--        CastSpell(spellOrder[1]) -- This one will always be empowered.
--        CastSpell(_E, enemy)
--        CastSpell(spellOrder[2])
--        return false
--    end

    if spellNameTable.resetAttackSpells[myHero.charName] ~= nil and myHero.charName ~= "Rengar" then
        if targetType and diff < 50 then
            if myHero:CanUseSpell(spellNameTable.resetAttackSpells[myHero.charName]) == READY then
                if myHero.charName == "Vayne" then
                    CastSpell(spellNameTable.resetAttackSpells[myHero.charName], mousePos.x, mousePos.z)
                else
                    CastSpell(spellNameTable.resetAttackSpells[myHero.charName], enemy)
                end
            end
        end
    end
    return false
end

function OnTick()
    if not OrbConfig.lastHitting then
        target = (OrbConfig.EnableTargetSelector and bestTarget(aaRange)) or (ValidTarget(GetTarget(), aaRange, true) and GetTarget() or nil)
    end

    if championData.BaseWindUpTime == nil or myHero.dead then
        if (OrbConfig.scriptActive or OrbConfig.lastHitting or OrbConfig.laneClear) and not myHero.dead then
            if OrbConfig.lastHitting then target = Minions.KillableMinion
            elseif OrbConfig.laneClear then target = Minions:GetSecondLowestHealthMinion()
            end
            if target then myHero:Attack(target) else myHero:MoveTo(mousePos.x, mousePos.z) end
        end
        return
    end

    local AttackCooldown, lastWindUp = 1000/(myHero.attackSpeed*championData.BaseAttackSpeed), championData.BaseWindUpTime/myHero.attackSpeed
    local championStage = {
        AttackAvailable = Minions.PacketSupport and GetTickCount() > lastAttack + lastWindUp and not championData.MissileLaunched or GetTickCount() > lastAttack + AttackCooldown,
        AbleToMove = GetTickCount() > lastAttack + lastWindUp,
        NextAttackAvailability = ((GetTickCount() - lastAttack) / AttackCooldown) >= 0 and ((GetTickCount() - lastAttack) / AttackCooldown) <= 1 and ((GetTickCount() - lastAttack) / AttackCooldown) or 1
    }
    if OrbConfig.scriptActive then
        if target and championStage.AttackAvailable then
            myHero:Attack(target)
            CastItemActives(target)
        elseif championStage.AbleToMove then
            if not spellsAvailable(target, OrbConfig.targetMode, championStage.NextAttackAvailability*100) then
                myHero:MoveTo(mousePos.x, mousePos.z)
            end
        end
    elseif OrbConfig.lastHitting then
        target = Minions.KillableMinion
        if target and championStage.AttackAvailable then
            myHero:Attack(target)
        elseif championStage.AbleToMove then
            myHero:MoveTo(mousePos.x, mousePos.z)
        end
    elseif OrbConfig.laneClear then
        if championStage.AttackAvailable then
            if Minions.KillableMinion then
                myHero:Attack(Minions.KillableMinion)
            elseif Minions.NearDeath then
                if VIP_USER then
                    Packet('S_MOVE', {x = mousePos.x, y = mousePos.z}):send()
                else
                    myHero:MoveTo(mousePos.x, mousePos.z)
                end
            else
                local lowHPtarget = Minions:GetSecondLowestHealthMinion()
                if ValidTarget(lowHPtarget, aaRange) then
                    myHero:Attack(lowHPtarget)
                elseif target then
                    myHero:Attack(target)
                else
                    if VIP_USER and #Minions.EnemyMinions.objects == 0 or (Minions.EnemyMinions.objects[1] and GetDistance(Minions.EnemyMinions.objects[1]) > aaRange) then
                        Packet("S_MOVE", {type = 7, x = mousePos.x, y = mousePos.z}):send()
                    else
                        myHero:MoveTo(mousePos.x, mousePos.z)
                    end
                end
            end
        elseif championStage.AbleToMove then
            myHero:MoveTo(mousePos.x, mousePos.z)
        end
    end
end

function OnDraw()
    if myHero.dead then return end

    aaRange = myHero.range + HitBox
    if OrbConfig.DrawAARANGE then DrawCircle(myHero.x, myHero.y, myHero.z, aaRange, colors.green) end
    if OrbConfig.autoAttackAwareness then
        for i, enemy in pairs(enemyTable) do
            if enemy and not enemy.dead and enemy.visible then
                local enemyRange = enemy.range + HitBox
                if GetDistance(enemy) <= enemyRange then
                    DrawCircle(enemy.x, enemy.y, enemy.z, enemyRange, colors.red)
                else
                    local fadeDist = 255*(1.27 - GetDistance(enemy)/1000)
                    local falseChecked = fadeDist > 0 and (fadeDist > 255 and 255 or fadeDist) or 0
                    DrawCircle(enemy.x, enemy.y, enemy.z, enemyRange, ARGB(255,0,falseChecked,0))
                end
            end
        end
    end

    if ValidTarget(target, nil, true) then DrawCircle(target.x, target.y, target.z, 100, colors.green) end
end