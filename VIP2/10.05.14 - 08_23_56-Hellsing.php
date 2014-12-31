<?php exit() ?>--by Hellsing 85.16.229.20
--[[
    ||===========================================================||
    ||                  Karthus King Killsteal                   ||
    ||                       by Hellsing                         ||
    ||===========================================================||
    
    
    Credits:
      - zikkah: love his scripts, got my BoL basics from his scripts,
                aswell as some code snippets
]]

-- This script requires VIP for the following reasons:
    -- Requirement of Prodiction
if myHero.charName ~= "Karthus" or not VIP_USER then return end

------------ Globals ------------
local player = myHero
local target = nil

local ts      = nil
local menu    = nil
local minions = nil

local prediction = nil

local currentHealth = nil
local currentMana   = nil

local enemyCount = 0
local enemyTable = {}

local lastAttack     = 0
local lastWindUpTime = 0
local lastAttackCD   = 0

local spells = {[_Q] = { range = 875,  level = 0, ready = false, manaUsage = 0, cooldown = 0, prodict = nil },
                [_W] = { range = 1000, level = 0, ready = false, manaUsage = 0, cooldown = 0, prodict = nil },
                [_E] = { range = 550,  level = 0, ready = false, manaUsage = 0, cooldown = 0, active = false },
                [_R] = { range = -1,   level = 0, ready = false, manaUsage = 0, cooldown = 0, }}

local hasIgnite    = false
local slotIgnite   = nil
local readyIgnite  = nil
local damageIgnite = 0
------------ Globals end ------------

------------ Constants ------------
local Q, W, E, R = _Q, _W, _E, _R

local IGNITE_RANGE = 600
local MAX_RANGE    = spells[W].range

local COLOR_RANGE_READY         = ARGB(255, 200, 0,   200)
local COLOR_RANGE_COMBO_READY   = ARGB(255, 255, 128, 0)
local COLOR_RANGE_NOT_READY     = ARGB(255, 50,  50,  50)
local COLOR_INDICATOR_READY     = ARGB(255, 0,   255, 0)
local COLOR_INDICATOR_NOT_READY = ARGB(255, 255, 220, 0)
local COLOR_INFO                = ARGB(255, 255, 50,  0)

local AUTOSTATS_ORDER = {Q, E, Q, W, Q, R, Q, E, Q, E, R, E, E, W, W, R, W, W}
------------ Constants end ------------

------------ AutoUpdate ------------
local SCRIPT_VERSION = 1.00

local updateCheck = true
local updateNeeded = false
local updateRemind = true

local urlScript = "https://raw.github.com/Hellsing/BotOfLegends/master/KarthusKingKillsteal.lua"
local pathScript = BOL_PATH .. "Scripts\\KarthusKingKillsteal.lua"
------------ AutoUpdate end ------------


function OnLoad()

    -- Requirements
    require "Prodiction"

    -- Setup needed stuff
    setupVars()
    setupProdiction()
    setupMenu()

    -- Add callback functions
    AddTickCallback(updateGlobals)
    AddTickCallback(updateDamages)
    AddTickCallback(checkCombo)
    AddTickCallback(checkAssistedSkills)
    AddTickCallback(checkFarm)

    -- Create enemy table structure
    for i = 1, heroManager.iCount do

        local champ = heroManager:GetHero(i)

        if champ.team ~= player.team then

            enemyCount = enemyCount + 1
            enemyTable[champ] = { damageQ = 0, damageE = 0, damageR = 0, indicatorText = "", damageGettingText = "", ready = true}

        end
    end

end


function OnTick()

    -- Use auto assign stats
    if menu.autoStats and player.level > getAssignedStats() then
        OnLevelUp(player, player.level, player.level - getAssignedStats())
    end

end


function OnDraw()

    -- Prevent spamming of error
    if not menu.drawings then return end

    -- Draw circle ranges
    if menu.drawings.drawQ then
        DrawCircle(player.x, player.y, player.z, spells[Q].range, (spells[Q].ready and COLOR_RANGE_READY or COLOR_RANGE_NOT_READY))
	end
    if menu.drawings.drawW then
        DrawCircle(player.x, player.y, player.z, spells[W].range, (spells[W].ready and COLOR_RANGE_READY or COLOR_RANGE_NOT_READY))
	end
    if menu.drawings.drawE then
        DrawCircle(player.x, player.y, player.z, spells[E].range, (spells[E].ready and COLOR_RANGE_READY or COLOR_RANGE_NOT_READY))
	end
    if menu.drawings.drawR then
        DrawCircle(player.x, player.y, player.z, spells[R].range, (spells[R].ready and COLOR_RANGE_READY or COLOR_RANGE_NOT_READY))
	end

    -- Show damage indicators
    if menu.drawings.showDamage then
        for i, enemy in ipairs(enemyTable) do

            if ValidTarget(enemy) then

                local barPos = WorldToScreen(D3DXVECTOR3(enemy.x, enemy.y, enemy.z))
                local posX = barPos.x - 35
                local posY = barPos.y - 50

                -- Doing damage
                DrawText(enemyTable[enemy].indicatorText, 15, posX, posY, (enemyTable[enemy].ready and COLOR_INDICATOR_READY or COLOR_INDICATOR_NOT_READY))

            end
        end
    end

end


-- Not in BoL yet but will be with next update
function OnLevelUp(unit, level, remainingLevelPoints)

    -- Prevent errors
    if not unit or not unit.valid or not unit.isMe then return end

    local count = remainingLevelPoints
    local countTable = {}

    for i, spell in ipairs(AUTOSTATS_ORDER) do   

        -- Only check till our level and only for the amount of points to assign
        if i > level or count == 0 then break end

        countTable[spell] = { value = countTable[spell] and countTable[spell].value + 1 or 1}

        if countTable[spell].value > player:GetSpellData(spell).level then
            -- Level spell since it needs to be highter than our level is atm
            LevelSpell(spell)
            count = getAssignedStats()
        end
    end

end


function OnProcessSpell(unit, spell)

    -- Prevent errors
    if not unit or not unit.valid or not unit.isMe then return end

    -- AutoAttack
    if spell.name:lower():find("attack") then
        lastAttack = GetTickCount() - GetLatency() / 2
        lastWindUpTime = spell.windUpTime * 1000
        lastAttackCD = spell.animationTime * 1000
    end

end


function OnGainBuff(unit, buff)

    -- Prevent errors
    if not unit or not unit.valid or not unit.isMe then return end

    -- Defile
    if buff.name == "Defile" then
        spells[E].active = true
    end

end


function OnLoseBuff(unit, buff)

    -- Prevent errors
    if not unit or not unit.valid or not unit.isMe then return end

    -- Defile
    if buff.name == "Defile" then
        spells[E].active = false
    end

end


function setupProdiction()

    -- Initialize Prodiction
    prediction = ProdictManager.GetInstance()
    
    -- Predict Q and W
    spells[Q].prodict = prediction:AddProdictionObject(Q, spells[Q].range, math.huge, 0.500, 100)
    spells[W].prodict = prediction:AddProdictionObject(W, spells[Q].range, math.huge, 0.500, 100)

end


function setupVars()

    -- Initialize TargetSelector
    ts = TargetSelector(TARGET_LOW_HP_PRIORITY, 1000, true)
    ts.name = player.charName
    
    -- Initialize MinonManager
    minions = minionManager(MINION_ENEMY, spells[Q].range, player, MINION_SORT_HEALTH_ASC)

    -- Check if player has ignite
    slotIgnite = ((player:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (player:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
	hasIgnite = slotIgnite ~= nil

end


function setupMenu()

    -- Create the menu
    menu = scriptConfig("Karthus King Killsteal", "karthus")

    -- Add the target selector
    menu:addTS(ts)

    -- General settings
    menu:addParam("sep",      "-------- General Settings --------",   SCRIPT_PARAM_INFO,      "")
    menu:addParam("combo",    "Combo",                                SCRIPT_PARAM_ONKEYDOWN, false, 32)
    menu:addParam("orbwalk",  "Orbwalking",                           SCRIPT_PARAM_ONOFF,     true)
    menu:addParam("move",     "Move to mouse",                        SCRIPT_PARAM_ONOFF,     true)
    menu:addParam("autoE",    "Auto E when enemy in range",           SCRIPT_PARAM_ONOFF,     false)
    menu:addParam("hitChance","Only cast when hitchance higher than", SCRIPT_PARAM_SLICE,     0.7, 0, 1, 2)

    -- Optional settings
    menu:addParam("sep",       "-------- Optional Settings --------", SCRIPT_PARAM_INFO, "")
    menu:addParam("autoStats", "Automatic assign stats",              SCRIPT_PARAM_ONOFF, false)
    
    -- Submenu -> Combo settings
    menu:addSubMenu(" --> Combo Settings", "combo")
    
    menu.combo:addParam("useQ",          "Use Q in combo",            SCRIPT_PARAM_ONOFF, true)
    menu.combo:addParam("useW",          "Use W in combo",            SCRIPT_PARAM_ONOFF, true)
    menu.combo:addParam("useE",          "Use E in combo",            SCRIPT_PARAM_ONOFF, true)

    -- Submenu -> Draw settings
    menu:addSubMenu(" --> Draw Settings", "drawings")

    -- General drawing
    menu.drawings:addParam("sep",        "-------- General --------", SCRIPT_PARAM_INFO,  "")
    menu.drawings:addParam("drawInfo",   "Draw general info",         SCRIPT_PARAM_ONOFF, true)

    -- Ranges
    menu.drawings:addParam("sep",        "-------- Ranges --------",  SCRIPT_PARAM_INFO,  "")
    menu.drawings:addParam("drawQ",      "Draw Q Range",              SCRIPT_PARAM_ONOFF, true)
    menu.drawings:addParam("drawW",      "Draw W Range",              SCRIPT_PARAM_ONOFF, false)
    menu.drawings:addParam("drawE",      "Draw E Range",              SCRIPT_PARAM_ONOFF, true)

    -- Utlity
    menu.drawings:addParam("sep",        "-------- Utility --------", SCRIPT_PARAM_INFO,  "")
    menu.drawings:addParam("showDamage", "Show damage indicator",     SCRIPT_PARAM_ONOFF, true)

    -- Options to show permanently
    menu:permaShow("hitChance")

end


function checkCombo()

    if menu.combo then

        -- Orbwalking or moving to cursor
        if menu.orbwalk then
            orbWalk()
        elseif menu.move then
            moveToCursor()
        end

        -- Spell combo
        if ValidTarget(target) then

            -- Q
            if spells[Q].ready then
                local pos, time, hitchance = spells[Q].prodict:GetPrediction(target)

                PrintChat("pos = " .. tostring(pos))
                PrintChat("time = " .. tostring(time))
                PrintChat("hitchance = " .. tostring(hitchance))

                if math.pow(spells[Q].range, 2) >= GetDistanceSqr(pos) then
                    CastSpell(Q, pos.x, pos.z)
                end
            end

        end
    end

end


function checkAssistedSkills()

    if spells[E].ready then
        if spells[E].active then
            if CountEnemyHeroInRange(spells[E].range) == 0 then
                CastSpell(E)
            end
        elseif CountEnemyHeroInRange(spells[E].range) > 0 then
            CastSpell(E)
        end
    end

end


function checkFarm()



end


function updateGlobals()

    -- Update target selector
    ts:update()
    
    -- Update minon manager
    minions:update()

    -- Set the current target
    target = ts.target

    -- Update spells
    for i = 0, 3 do
        spells[i].ready     = player:CanUseSpell(i) == READY
        spells[i].manaUsage = player:GetSpellData(i).mana
        spells[i].cooldown  = player:GetSpellData(i).currentCd
        spells[i].level     = player:GetSpellData(i).level
    end

    -- Ignite
    if hasIgnite then
        readyIgnite = player:CanUseSpell(slotIgnite) == READY        
        damageIgnite = ValidTarget(target) and getDmg("IGNITE", target, player) or 0
    end

    -- Current health and mana
    currentHealth = player.health
    currentMana   = player.mana
    
    -- Check menu
    if menu.orbwalk and not menu.move then
        menu.move = true
    end

end


function updateDamages()

    -- Don't calculate this if not wanted
    if not menu.drawings.showDamage then return end

    for i, enemy in ipairs(enemyTable) do

		if ValidTarget(enemy) then

            -- Auto attack damage
            local damageAA = getDmg("AD", enemy, player)

            -- Skills damage
            local damageQ  = getDmg("Q", enemy, player)
            local damageE  = getDmg("E", enemy, player)
            local damageR  = getDmg("R", enemy, player)

            -- Update enemy table
            enemyTable[enemy].damageQ = damageQ
            enemyTable[enemy].damageE = damageW
            enemyTable[enemy].damageE = damageE
            enemyTable[enemy].damageR = damageR

            local distanceEnemy = GetDistanceSqr(enemy)
            local neededQ = math.ceil(ememy.health / damageQ)
            local comboQ = 5

            -- Within Q range
            if distanceEnemy < math.pow(MAX_RANGE * 2, 2) then

                -- Q combo kill
                if enemy.health < damageQ * comboQ then

                    enemyTable[enemy].indicatorText = (neededQ > 1 and (neededQ .. "x ") or "") .. "Q Kill"
                    enemyTable[enemy].ready = spells[Q].manaUsage * neededQ <= currentMana

                -- Harass more
                else
                
                    local damageTotal = damageQ * comboQ
                    local healthLeft = math.round(enemy.health - damageTotal)
                    local percentLeft = math.round(healthLeft / enemy.maxHealth * 100)

                    enemyTable[enemy].indicatorText = percentLeft .. "% Harass"
                    enemyTable[enemy].ready = spells[Q].ready or spells[W].ready or spells[E].ready or currentMana >= spells[Q].manaUsage or currentMana >= spells[E].manaUsage

                end

            -- Out of Q range
            else

                -- No ult kill
                if enemy.health > damageR then

                    enemyTable[enemy].indicatorText = "Out of range. (" .. (neededQ > 1 and (neededQ .. "x ") or "") .. "Q Kill)"
                    enemyTable[enemy].ready = false

                -- Ult kill possible
                else

                    enemyTable[enemy].indicatorText = "ULT KILL!"
                    enemyTable[enemy].ready = spells[R].ready

                end
            end
        end
    end

end


function getAssignedStats()
    return spells[Q].level + spells[W].level + spells[E].level + spells[R].level
end


function orbWalk()

    if target ~= nil and GetDistanceSqr(target) <= getTrueRange() then
        if playerCanAttack() then
            player:Attack(target)
        elseif playerCanMove() then
            moveToCursor()
        end
    else
        moveToCursor()
    end
end


function getTrueRange()
    return math.pow(player.range, 2) + GetDistanceSqr(player.minBBox)
end


function playerCanMove()
    return (GetTickCount() + GetLatency() / 2 > lastAttack + lastWindUpTime + 20)
end


function playerCanAttack()
    return (GetTickCount() + GetLatency() / 2 > lastAttack + lastAttackCD)
end


function moveToCursor()

	if GetDistanceSqr(mousePos) > math.pow(150, 2) or lastAnimation == "Idle1" then
    
		local moveToPos = player + (Vector(mousePos) - player):normalized()*300
		player:MoveTo(moveToPos.x, moveToPos.z)
        
	end	
    
end

--UPDATEURL=
--HASH=917F179AC4DE93E1D7F0294987F0BDD3
