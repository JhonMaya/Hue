<?php exit() ?>--by Manciuszz 78.62.151.40
local target, OrbConfig, ts, aaRange
local updateTargets = true
_G.evading = false
local testAPI, colors = {}, { red = ARGB(255,255,0,0), green = ARGB(255,0,255,0)}
local HitBox = 65*1.9
local enemyTable = GetEnemyHeroes()
local lastAttack = GetTickCount()
local spellNameTable = {
    demResetSpell = {
        ['VayneTumble'] = true, ['SivirW'] = true, ["Powerfist"] = true, ["DariusNoxianTacticsONH"] = true, ["Takedown"] = true,
        ["SivirW"] = true, ["BlindingDart"] = true, ["VayneTumble"] = true, ["JaxEmpowerTwo"] = true, ["MordekaiserMaceOfSpades"] = true,
        ["SiphoningStrikeNew"] = true,  ["RengarQ"] = true, ["MonkeyKingDoubleAttack"] = true, ["YorickSpectral"] = true, ["ViE"] = true,
        ["GarenSlash3"] = true, ["HecarimRamp"] = true, ["XenZhaoComboTarget"] = true, ["LeonaShieldOfDaybreak"] = true, ["ShyvanaDoubleAttack"] = true,
        ["shyvanadoubleattackdragon"] = true, ["TalonNoxianDiplomacy"] = true, ["TrundleTrollSmash"] = true, ["VolibearQ"] = true, ["PoppyDevastatingBlow"] = true
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
    }
}

function OnLoad()
    OrbConfig = scriptConfig("Orbing 2013", "Orbing2013")
    OrbConfig:addParam("scriptActive", "Orbwalking", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("A"))
    OrbConfig:addParam("autoAttackAwareness", "Enable autoAttackAwareness", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("N"))
    OrbConfig:addParam("attackFocused", "Kite focused targets", SCRIPT_PARAM_ONOFF, true)
    OrbConfig:addParam("DrawAARANGE", "Draw you AA-Range circle", SCRIPT_PARAM_ONOFF, true)

    ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, myHero.range + HitBox, DAMAGE_PHYSICAL, true) -- PRO TIP: Priority 1 is the highest, 5 is the lowest.
    ts.name = "Orbing"
    OrbConfig:addTS(ts)

    print("Pimpin ain't easy -Doublelift 2013")
end

function OnProcessSpell(unit, spell)
    if myHero.dead then return end

    if unit.isMe then
        if (spellNameTable.demSpellAttacks[spell.name] or spell.name:lower():find('attack')) and not spellNameTable.demNoneAttacks[spell.name] then
            lastAttack = GetTickCount() - GetLatency()/3
            if testAPI.BaseWindUpTime == nil then
                testAPI = {
                    BaseAttackSpeed = 1000/(1000*spell.animationTime*myHero.attackSpeed),
                    BaseWindUpTime = 1000*spell.windUpTime*myHero.attackSpeed,
                }
            end
        elseif spellNameTable.demResetSpell[spell.name] then
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

function OnTick()
    ts.targetSelected = OrbConfig.attackFocused
    ts.range = myHero.range + HitBox
    target = ts.target
    if updateTargets then ts:update() end
    if testAPI.BaseWindUpTime == nil or myHero.dead then
        if OrbConfig.scriptActive and not myHero.dead then
            if ValidTarget(target, ts.range, true) then
                myHero:Attack(target)
            else
                if not _G.evading then myHero:MoveTo(mousePos.x, mousePos.z) end
            end
        end
        return
    end

    local AttackCooldown, lastWindUp = 1000/(myHero.attackSpeed*testAPI.BaseAttackSpeed), testAPI.BaseWindUpTime/myHero.attackSpeed
    if OrbConfig.scriptActive then
        if GetTickCount() > lastAttack + AttackCooldown and (target and target.team ~= myHero.team and GetDistance(target) <= ts.range) then
            updateTargets = false
            myHero:Attack(target)
            CastItemActives(target)
        elseif target == nil or GetTickCount() > lastAttack + lastWindUp then
            if not _G.evading then myHero:MoveTo(mousePos.x, mousePos.z) end
            updateTargets = true
        end
    end
end

function OnDraw()
    if myHero.dead then return end

    if OrbConfig.DrawAARANGE then DrawCircle(myHero.x, myHero.y, myHero.z, ts.range, colors.green) end
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