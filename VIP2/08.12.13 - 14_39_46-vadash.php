<?php exit() ?>--by vadash 109.188.127.110
_G.Champs = {
    ["Nidalee"] = {
        [_Q] = { speed = 1300, delay = 0.125, range = 1500, minionCollisionWidth = 80},
    },
}

_G.predictions = {}
_G.collisions = {}
_G.str = { [_Q] = "Q", [_W] = "W", [_E] = "E", [_R] = "R" }
_G.keybindings = { [_Q] = "Z", [_W] = "X", [_E] = "C", [_R] = "V" }
_G.Config = scriptConfig("I'M Aiming [MMA]: Settings", "ImAiming")
_G.ConfigType = SCRIPT_PARAM_ONKEYDOWN

_G.ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1500, DAMAGE_MAGIC, true)

 -- Code ------------------------------------------------------------------------
function OnLoad()
    -- Angles
    ANGLE_TOWARDS = 180
    ANGLE_AWAY = 0
    ANGLE_PARALLEL_TOWARDS = 90
    ANGLE_PARALLEL_AWAY = 270
    ANGLE_UNKNOWN = -1

    wp = ProdictManager.GetInstance() 
    if Champs[myHero.charName] ~= nil then 
        for i, spell in pairs(Champs[myHero.charName]) do
            Config:addParam(str[i], "Predict " .. str[i], ConfigType, false, GetKey(keybindings[i]))
            predictions[str[i]] = wp:AddProdictionObject(i, spell.range, spell.speed, spell.delay, spell.minionCollisionWidth, myHero)
            if spell.minionCollisionWidth > 0 then collisions[str[i]] = FastCol(predictions[str[i]]) end
        end
    end 
    ts.name = "ImAiming"
    Config:addTS(ts)

    PrintChat(" >> I'M Aiming by Klokje +")
end

function GetMMATarget()
    if _G.MMA_Target ~= nil and _G.MMA_Target.type:lower() == "obj_ai_hero" then return _G.MMA_Target end
    ts:update()
    return ts.target
end

function OnTick()
    Target = GetMMATarget()
    if Target then 
        for i, param in pairs(predictions) do
            if Config[i] then 
                param:GetPredictionCallBack(Target, ProdictCallback)
            end 
        end
    end
end

function ProdictCallback(unit, pos, spell) 
    if GetDistanceSqr(unit) < spell.RangeSqr and myHero:CanUseSpell(spell.Name) == READY then 
        -- specific champ logic
        if myHero.charName == "Jinx" and spell.Name == _W and GetDistance(unit) < 200 then return end
        
        -- angle check
        local angleDif = GetAngle(unit, pos)
        if angleDif == ANGLE_UNKNOWN or not IsGoodAngle(angleDif, 40) then return end

        -- collision check
        if spell.minionCollisionWidth == nil or spell.minionCollisionWidth == 0 then
            CastSpell(spell.Name, pos.x, pos.z)
        else
            local willCollide = collisions[str[spell.Name]]:GetMinionCollision(pos, myHero)
            if not willCollide then CastSpell(spell.Name, pos.x, pos.z) end
        end 
    end 
end

--[[function OnDraw()
    DrawCircle(2000, myHero.y, 2000, 100, ARGB(255, 255, 0, 0))
    if timer == nil or GetTickCount() - timer > 500 then
        timer = GetTickCount()
        local a = GetAngle({x = 2000, y = myHero.y, z = 2000}, {x = mousePos.x, y = myHero.y, z = mousePos.z})
        print(IsGoodAngle(a , 45))
    end
end]]

-- Angles
function IsGoodAngle(angleDiff, variance)
    local direction = _directionality(angleDiff, variance)
    if direction == ANGLE_TOWARDS or direction == ANGLE_AWAY then
        return true
    else
        return false
    end
end
function GetAngle(enemy, predPos)
    v1 = (Vector(enemy) - Vector(myHero)):normalized()
    v2 = (Vector(predPos) - Vector(enemy)):normalized()

    if predPos.x == enemy.x and predPos.z == enemy.z then
        return ANGLE_UNKNOWN
    end

    shootTheta = math.deg(math.atan2(v1.z, v1.x))
    enemyTheta = math.deg(math.atan2(v2.z, v2.x))

    if shootTheta < 0 then
        shootTheta = shootTheta + 360  
    end

    if enemyTheta < 0 then
        enemyTheta = enemyTheta + 360
    end

    angleDiff = math.abs(enemyTheta - shootTheta)

    return angleDiff
end
function _directionality(angleDiff, variance)
    if _betweenRounded(angleDiff, variance, ANGLE_AWAY) then
        return ANGLE_AWAY
    elseif _betweenRounded(angleDiff, variance, ANGLE_TOWARDS) then
        return ANGLE_TOWARDS
    elseif _betweenRounded(angleDiff, variance, ANGLE_PARALLEL_TOWARDS) then
        return ANGLE_PARALLEL_TOWARDS
    elseif _betweenRounded(angleDiff, variance, ANGLE_PARALLEL_AWAY) then
        return ANGLE_PARALLEL_AWAY
    else
        return ANGLE_UNKNOWN
    end
end
function _betweenRounded(angleDiff, variance, angle) -- diff = known angle between two people, variance is allowed slippage, angle = goal you want if true
    low = angle - variance
    high = angle + variance

    if low <= 0 then
        return (low + 360 <= angleDiff or angleDiff <= high)
    else
        return (low <= angleDiff and angleDiff <= high)
    end
end