<?php exit() ?>--by CLG Aphromoo 70.193.135.160
--[[
    ||===========================================================||
    ||                     Nulled Kassadin                       ||
    ||                       by Hellsing                         ||
    ||===========================================================||
    
    
    Credits:
      - zikkah: love his scripts, got my BoL basics from his scripts,
                aswell as some code snippets
                
      - Sida:   his outdated script gave me the idea
]]

if myHero.charName ~= "Kassadin" then return end

------------ Globals ------------
local player = myHero
local target = nil

local ts   = nil
local menu = nil

local currentHealth = nil
local currentMana   = nil

local playingSafe = false

local enemyCount = 0
local enemyTable = {}

local lastAttack     = 0
local lastWindUpTime = 0
local lastAttackCD   = 0

local readyQ = false
local readyW = false
local readyE = false
local readyR = false

local manaQ = nil
local manaW = nil
local manaE = nil
local manaR = nil

local cooldownQ = 0
local cooldownW = 0
local cooldownE = 0
local cooldownR = 0

local levelQ = 0
local levelW = 0
local levelE = 0
local levelR = 0

local hasIgnite    = false
local slotIgnite   = nil
local readyIgnite  = nil
local damageIgnite = 0

local stacksE = 0
local stacksR = 0
local gainedBuffE = false
local gainedBuffR = false
------------ Globals end ------------

------------ Constants ------------
local rangeQ      = 650
local rangeW      = 200
local rangeE      = 400
local rangeR      = 750
local rangeIgnite = 600
local maxRange    = rangeR + rangeQ

local colorRangeReady        = ARGB(255, 200, 0,   200)
local colorRangeComboReady   = ARGB(255, 255, 128, 0)
local colorRangeNotReady     = ARGB(255, 50,  50,  50)
local colorIndicatorReady    = ARGB(255, 0,   255, 0)
local colorIndicatorNotReady = ARGB(255, 255, 220, 0)
local colorInfo              = ARGB(255, 255, 50,  0)

local statsSequence = {_Q, _W, _Q, _E, _Q, _R, _Q, _E, _Q, _E, _R, _E, _E, _W, _W, _R, _W, _W}

local buffNameE = "forcepulsecounter"
local buffNameR = "RiftWalk"
------------ Constants end ------------


-- ############################################################### Callbacks start ###############################################################

function OnLoad()
    
    setupVars()
    setupMenu()
    
    -- Create table structure
    for i = 1, heroManager.iCount do
    
        local champ = heroManager:GetHero(i)
        
        if champ.team ~= player.team then
        
            enemyCount = enemyCount + 1
            enemyTable[enemyCount] = { player = champ, name = champ.charName, damageQ = 0, damageW = 0, damageE = 0, damageR = 0, indicatorText = "", damageGettingText = "", ready = true}

        end
    end

end


function OnTick()

    updateGlobals()
    calculateDamageIndicators()
    
    -- Use auto assign stats
    if menu.autoStats and player.level ~= getAssignedStats() then
    
        local currentAssignedStats = getAssignedStats()

        -- Precheck if we got stats to assign
        if currentAssignedStats < player.level then

            local countTable = {}
        
            for i, spell in ipairs(statsSequence) do   
            
                -- Only check till our level
                if i > player.level then break end
            
                countTable[spell] = { value = countTable[spell] and countTable[spell].value + 1 or 1}
                
                if countTable[spell].value > player:GetSpellData(spell).level then
                    -- Level spell since it needs to be highter than our level is atm
                    LevelSpell(spell)
                end
            end

        end
    end

    -- Check if combo is active
    if menu.combo then
    
        -- Cast combo
        combo()
    
        -- Use orbwalk
        if menu.orbwalk then orbWalk() end
    end
    
end


function OnGainBuff(unit, buff)

    -- ForcePulse (E)
    if unit.isMe and buff.name == buffNameE then
        stacksE = 1
        gainedBuffE = true
    -- RiftWalk (R)
    elseif unit.isMe and buff.name == buffNameR then
        stacksR = 1
        gainedBuffR = true
    end
    
end


function OnUpdateBuff(unit, buff)

    -- ForcePulse (E)
    if unit.isMe and buff.name == buffNameE and gainedBuffE then
        stacksE = stacksE + 1
    -- RiftWalk (R)
    elseif unit.isMe and buff.name == buffNameR and gainedBuffR then
        if stacksR <= 10 then stacksR = stacksR + 1 end
    end

end


function OnLoseBuff(unit, buff)

    -- ForcePulse (E)
    if unit.isMe and buff.name == buffNameE and gainedBuffE then
        stacksE = 6
        gainedBuffE = false
    -- RiftWalk (R)
    elseif unit.isMe and buff.name == buffNameR and gainedBuffR then
        stacksR = 0
        gainedBuffR = false
    end

end


function OnDraw()    

    -- Prevent spamming of error
    if not menu.drawings then return end
    
    -- Draw general info
    if menu.drawings.drawInfo then
        if playingSafe then
            local barPos = WorldToScreen(D3DXVECTOR3(player.x, player.y, player.z))
            local posX = barPos.x - 35
            local posY = barPos.y - 30
            
            DrawText("Staying ranged!", 15, posX, posY + 15, colorInfo)
        end
    end

    -- Draw circle ranges
    if menu.drawings.drawQ then
        DrawCircle(player.x, player.y, player.z, rangeQ, (readyQ and colorRangeReady or colorRangeNotReady))
	end
    if menu.drawings.drawW then
        DrawCircle(player.x, player.y, player.z, rangeW, (readyW and colorRangeReady or colorRangeNotReady))
	end
    if menu.drawings.drawE then
        DrawCircle(player.x, player.y, player.z, rangeE, (readyE and colorRangeReady or colorRangeNotReady))
	end
    if menu.drawings.drawR then
        DrawCircle(player.x, player.y, player.z, rangeR, (readyR and colorRangeReady or colorRangeNotReady))
	end
    -- Draw combo ranges
    if menu.drawings.drawCombo then
        if readyR and readyQ then
            DrawCircle(player.x, player.y, player.z, rangeR + rangeQ, colorRangeComboReady)
        elseif readyR and readyE then
            DrawCircle(player.x, player.y, player.z, rangeR + rangeE, colorRangeComboReady)
        end
	end

    -- Show damage indicators
    if menu.drawings.showDamage then
        for i = 1, enemyCount do
        
            local enemy = enemyTable[i].player

            if ValidTarget(enemy) then
            
                local barPos = WorldToScreen(D3DXVECTOR3(enemy.x, enemy.y, enemy.z))
                local posX = barPos.x - 35
                local posY = barPos.y - 50

                -- Doing damage
                DrawText(enemyTable[i].indicatorText, 15, posX, posY, (enemyTable[i].ready and colorIndicatorReady or colorIndicatorNotReady))
                
                -- Taking damage
                DrawText(enemyTable[i].damageGettingText, 15, posX, posY + 15, ARGB(255, 255, 0, 0))
                
            end
        end
    end

end


function OnProcessSpell(object, spell)

    if object == player then    
        if spell.name:lower():find("attack") then

            lastAttack = GetTickCount() - GetLatency() / 2
            lastWindUpTime = spell.windUpTime * 1000
            lastAttackCD = spell.animationTime * 1000

        end
    end

end

-- ############################################################## Callbacks end ##############################################################

-- ############################################################### Combo start ###############################################################

function combo()

    -- Return if no target
    if target == nil then return end

    -- Get distance between our champ and the target
    local distance = player:GetDistance(target)
    
    -- Return if no combo could be in range
    if (distance > maxRange) then return end

    -- Check if our basic spells are in range
    if distance > rangeQ and distance > rangeE then
    
        -- R + Q
        if distance <= rangeR + rangeQ and readyR and readyQ and currentMana >= manaR + manaQ then
            CastSpell(_R, target)
            CastSpell(_Q, target)
            
        -- R + E
        elseif distance <= rangeR + rangeE and readyR and (readyE or ((stacksE == 5 and cooldownE == 0) or (stacksE == 4 and cooldownE == 0 and readyW))) and currentMana >= (stacksE == 4 and manaW + manaR + manaE or manaR + manaE) then
        
            -- Check if we need to ready E first with W
            if stacksE == 4 then
                CastSpell(_W)
            end
            
            CastSpell(_R, target)
            CastSpell(_E, target)
            
        end
    
    end
    
    -- Q
    if distance <= rangeQ and readyQ and currentMana >= manaQ then
        CastSpell(_Q, target)
    end
    
    -- R
    if (distance <= rangeR / 2 or (distance <= rangeR and target.health <= getDmg("R", target, player))) and readyR and currentMana >= manaR then
        CastSpell(_R, target)
    end
    
    -- Check if we can ready E with W
    if not readyE and cooldownE == 0 and distance <= rangeE and readyW and stacksE == 5 and currentMana >= manaW + manaE then
        CastSpell(_W)
    end
    
    -- E
    if distance <= rangeE and readyE and currentMana >= manaE then
        CastSpell(_E, target)
    end
    
    -- W
    if distance <= rangeW and readyW and currentMana >= manaW then
        CastSpell(_W)
    end
    
end

-- ############################################################### Combo end ###############################################################

-- ######################################################## Recurring checks start #########################################################

function updateGlobals()

    -- Update target selector
    ts:update()

    -- Set the current target
    target = ts.target

    -- Spells ready status
    readyQ = player:CanUseSpell(_Q) == READY 
	readyW = player:CanUseSpell(_W) == READY
	readyE = player:CanUseSpell(_E) == READY
	readyR = player:CanUseSpell(_R) == READY
    
    -- Mana usage
    manaQ = player:GetSpellData(_Q).mana
	manaW = player:GetSpellData(_W).mana
	manaE = player:GetSpellData(_E).mana
	manaR = player:GetSpellData(_R).mana
    
    -- Cooldowns for spells
    cooldownQ = player:GetSpellData(_Q).totalCooldown
    cooldownW = player:GetSpellData(_W).totalCooldown
    cooldownE = player:GetSpellData(_E).totalCooldown
    cooldownR = player:GetSpellData(_R).totalCooldown
    
    -- Levels for spells
    levelQ = player:GetSpellData(_Q).level
    levelW = player:GetSpellData(_W).level
    levelE = player:GetSpellData(_E).level
    levelR = player:GetSpellData(_R).level
    
    -- Ignite
    if hasIgnite then
        readyIgnite = player:CanUseSpell(slotIgnite) == READY
        damageIgnite = getDmg("IGNITE", target, player)
    end
    
    -- Current health and mana
    currentHealth = player.health
    currentMana   = player.mana
    
    -- Staying ranged
    playingSafe = (player.health / player.maxHealth < .15 and true or false)

end


function calculateDamageIndicators()

    -- Don't calculate this if not wanted
    if not menu.drawings.showDamage then return end

    for i = 1, enemyCount do

        local enemy = enemyTable[i].player

		if ValidTarget(enemy) and enemy.visible then

            -- Auto attack damage
            local damageAA = getDmg("AD", enemy, player)
            
            -- Skills damage
            local damageQ  = getDmg("Q", enemy, player)
            local damageE  = getDmg("E", enemy, player)
            local damageR  = getDmg("R", enemy, player)
        
            -- Update enemy table
            enemyTable[i].damageQ = damageQ
            enemyTable[i].damageW = damageW
            enemyTable[i].damageE = damageE
            enemyTable[i].damageR = damageR
            
            -- Make combos, highest range to lowest
            if enemy.health < damageR then

                enemyTable[i].indicatorText = "R Kill"
                enemyTable[i].ready = readyR and manaR <= currentMana

            elseif enemy.health < damageQ then

                enemyTable[i].indicatorText = "Q Kill"
                enemyTable[i].ready = readyQ and manaQ <= currentMana

            elseif enemy.health < damageE then

                enemyTable[i].indicatorText = "E Kill"
                enemyTable[i].ready = readyE and manaE <= currentMana

            elseif enemy.health < damageR + damageQ then

                enemyTable[i].indicatorText = "R + Q Kill"
                enemyTable[i].ready = readyR and readyQ and manaR + manaQ <= currentMana

            elseif enemy.health < damageR + damageE then

                enemyTable[i].indicatorText = "R + E Kill"
                enemyTable[i].ready = readyR and readyE and manaR + manaE <= currentMana

            elseif enemy.health < damageR + damageQ + damageE then

                enemyTable[i].indicatorText = "R + Q + E Kill"
                enemyTable[i].ready = readyR and readyQ and readyE and manaR + manaQ + manaE <= currentMana

            elseif enemy.health < damageQ + damageE + damageR then

                enemyTable[i].indicatorText = "All-In Kill"
                enemyTable[i].ready = readyQ and readyE and readyR and manaQ + manaE + manaR <= currentMana

            else

                local damageTotal = damageQ + damageE + damageR
                local healthLeft = math.round(enemy.health - damageTotal)
                local percentLeft = math.round(healthLeft / enemy.maxHealth * 100)

                enemyTable[i].indicatorText = percentLeft .. "% Harass"
                enemyTable[i].ready = readyQ or readyE or readyR

            end

            -- Calculate damage we could receive
            local enemyDamageAA = getDmg("AD", player, enemy)

            local enemyReadyQ = enemy:CanUseSpell(_Q) == READY
            local enemyReadyW = enemy:CanUseSpell(_W) == READY
            local enemyReadyE = enemy:CanUseSpell(_E) == READY
            local enemyReadyR = enemy:CanUseSpell(_R) == READY

            -- Number of needed auto attacks for us to die
            local enemyNeededAA = math.ceil(player.health / enemyDamageAA)            
            enemyTable[i].damageGettingText = "I die with " .. enemyNeededAA .. " AA"

        end
    end	

end

-- ############################################################# Recurring checks end #############################################################

-- ############################################################### Initialize start ###############################################################

function setupVars()

    -- Initialize TargetSelector
    ts = TargetSelector(TARGET_NEAR_MOUSE, 600, true)
    ts.name = player.charName
    
    -- Check if player has ignite
    slotIgnite = ((player:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (player:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
	hasIgnite = slotIgnite ~= nil
    
end


function setupMenu()
    
    -- Create the menu
    menu = scriptConfig("Nulled Kassadin", "kassadin")
    
    -- Add the target selector
    menu:addTS(ts)
    
    -- General settings
    menu:addParam("sep",      "-------- General Settings --------",   SCRIPT_PARAM_INFO,      "")    
    menu:addParam("combo",    "Combo",                                SCRIPT_PARAM_ONKEYDOWN, false, 32)
    menu:addParam("orbwalk",  "Orbwalking",                           SCRIPT_PARAM_ONOFF,     true)
    menu:addParam("safeMode", "Stay ranged when health < 15%",        SCRIPT_PARAM_ONOFF,     false)
    
    -- Optional settings
    menu:addParam("sep",       "-------- Optional Settings --------", SCRIPT_PARAM_INFO, "")   
    menu:addParam("autoStats", "Automatic assign stats",              SCRIPT_PARAM_ONOFF, false)
    
    -- Submenu -> Draw settings
    menu:addSubMenu("Draw Settings", "drawings")
    
    -- General drawing
    menu.drawings:addParam("sep",        "-------- General --------", SCRIPT_PARAM_INFO,  "")   
    menu.drawings:addParam("drawInfo",   "Draw general info",         SCRIPT_PARAM_ONOFF, true)
    
    -- Ranges
    menu.drawings:addParam("sep",        "-------- Ranges --------",  SCRIPT_PARAM_INFO,  "")   
    menu.drawings:addParam("drawQ",      "Draw Q Range",              SCRIPT_PARAM_ONOFF, true)
    menu.drawings:addParam("drawW",      "Draw W Range",              SCRIPT_PARAM_ONOFF, false)
    menu.drawings:addParam("drawE",      "Draw E Range",              SCRIPT_PARAM_ONOFF, true)
    menu.drawings:addParam("drawR",      "Draw R Range",              SCRIPT_PARAM_ONOFF, true)
    menu.drawings:addParam("drawCombo",  "Draw Combo Range",          SCRIPT_PARAM_ONOFF, true)
    
    -- Utlity
    menu.drawings:addParam("sep",        "-------- Utility --------", SCRIPT_PARAM_INFO,  "")   
    menu.drawings:addParam("showDamage", "Show damage indicator",     SCRIPT_PARAM_ONOFF, true)
    
end

-- ############################################################### Initialize end ###############################################################

-- ############################################################### General start ###############################################################

function getAssignedStats()
    return levelQ + levelW + levelE + levelR
end


function orbWalk()

    if ts.target ~= nil and player:GetDistance(ts.target) <= getTrueRange() then
        if playerCanAttack() then
            player:Attack(ts.target)
        elseif playerCanMove() then
            moveToCursor()
        end
    else
        moveToCursor()
    end
end


function getTrueRange()
    return player.range + player:GetDistance(player.minBBox)
end


function playerCanMove()
    return (GetTickCount() + GetLatency() / 2 > lastAttack + lastWindUpTime + 20)
end


function playerCanAttack()
    return (GetTickCount() + GetLatency() / 2 > lastAttack + lastAttackCD)
end


function moveToCursor()

	if player:GetDistance(mousePos) > 150 or lastAnimation == "Idle1" then
    
		local moveToPos = player + (Vector(mousePos) - player):normalized()*300
		player:MoveTo(moveToPos.x, moveToPos.z)
        
	end	
    
end

-- ############################################################### General end ###############################################################

PrintChat(">> NulledKassadin loaded")