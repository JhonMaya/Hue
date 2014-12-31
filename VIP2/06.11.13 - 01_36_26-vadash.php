<?php exit() ?>--by vadash 108.162.254.25
if myHero.charName ~= "Cassiopeia" then return end

local version = "v0.1"

function OnLoad()
        loadMenus()
        loadClasses()
        loadVariables()
        loadSkillData()
        loadProdiction()
        loadVIPPrediction()
        loadMinions()
        loadSpells()
        loadEnemies()
                
        PrintChat("<font color='#CCCCCC'> >> Just Another Cassiopeia "..version.." Loaded! << </font>")
end

function OnUnload()
        PrintChat("<font color='#CCCCCC'> >> Just Another Cassiopeia "..version.." UnLoaded! << </font>")
end

function loadMenus()
        settingsMenu()
        ultMenu()
		aaMenu()
        mtmMenu()
        drawingMenu()
        colourMenu()
        displayMenu()
        permaShow()
end

function settingsMenu()
        JACmain = scriptConfig("Just Another Cassiopeia: Main Settings", "JAC_settings")
        JACmain:addParam("info", "              Just Another Cassiopeia", SCRIPT_PARAM_INFO, "")
		JACmain:addParam("combo", "COMBO", SCRIPT_PARAM_ONKEYDOWN, false, 32)
		JACmain:addParam("Harass1", "Q+E Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("S"))
		JACmain:addParam("Harass2", "Q Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
        JACmain:addParam("jungleClear", "Jungle Clear", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
        JACmain:addParam("laneClear", "Lane Clear", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
        JACmain:addParam("autoFarm", "Auto Farm with E", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("T"))
--      JACmain:addParam("autoPT", "Passive Tracking", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("U"))
		JACmain:addParam("autoE", "Auto E on Poisoned Enemies", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("Y"))
        JACmain:addParam("useItems", "Use Items in Combo", SCRIPT_PARAM_ONOFF, true)
end

function ultMenu()
        JACult = scriptConfig("Just Another Cassiopeia: Ult Settings", "JAC_ultsettings")
        JACult:addParam("castUlt", "Cast Ult", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("R"))
        JACult:addParam("ultKS", "Kill Steal with Ult", SCRIPT_PARAM_ONOFF, false)
        JACult:addParam("info1", "          ------------------------------", SCRIPT_PARAM_INFO, "")
        JACult:addParam("singleTargetUlt", "Use Ult in Single Target Combo", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("D"))
        JACult:addParam("targetMinHP", "Use Ult if Target HP > %", SCRIPT_PARAM_SLICE, 20, 1, 100, 0)
        JACult:addParam("targetMaxHP", "Use Ult if Target HP < %", SCRIPT_PARAM_SLICE, 85, 1, 100, 0)
        JACult:addParam("myMinHP", "Use Ult if my HP < %", SCRIPT_PARAM_SLICE, 70, 1, 100, 0)
        JACult:addParam("myMaxHP", "Use Ult if my HP > %", SCRIPT_PARAM_SLICE, 20, 1, 100, 0)
        JACult:addParam("minHPratio", "Min Negative HP Ratio", SCRIPT_PARAM_SLICE, 50, 30, 80, 0)
        JACult:addParam("maxHPratio", "Max Positive HP Ratio", SCRIPT_PARAM_SLICE, 175, 125, 250, 0)
        JACult:addParam("ignoreHP", "Ignore All HP Variables", SCRIPT_PARAM_ONOFF, false)
        JACult:addParam("info2", "          ------------------------------", SCRIPT_PARAM_INFO, "")
		JACult:addParam("setUltEnemiesSBTW", "Ult Weight (in SBTW Combo)", SCRIPT_PARAM_SLICE, 2, 1, 6, 0)
		JACult:addParam("setUltEnemiesAUTO", "Ult Weight (Automatic)", SCRIPT_PARAM_SLICE, 3, 1, 6, 0)
end

function aaMenu()
		JACaa = scriptConfig("Just Another Cassiopeia: AA Settings", "JAC_AAsettings")
		JACaa:addParam("aaCombo", "AA with Combo key", SCRIPT_PARAM_ONOFF, false)
		JACaa:addParam("aaHarass1", "AA with Q+E Harass key", SCRIPT_PARAM_ONOFF, false)
		JACaa:addParam("aaHarass2", "AA with Q Harass key", SCRIPT_PARAM_ONOFF, false)
end

function mtmMenu()
        JACmtm = scriptConfig("Just Another Cassiopeia: Move To Mouse", "JAC_mtmsettings")
        JACmtm:addParam("mtmCombo", "MTM with Combo Key", SCRIPT_PARAM_ONOFF, true)
        JACmtm:addParam("mtmHarass1", "MTM with Q+E Harass Key", SCRIPT_PARAM_ONOFF, true)
        JACmtm:addParam("mtmHarass2", "MTM with Q Harass Key", SCRIPT_PARAM_ONOFF, true)
--      JACmtm:addParam("mtmJungle", "MTM with Jungle Clear Key", SCRIPT_PARAM_ONOFF, true)
--      JACmtm:addParam("mtmLane", "MTM with Lane Clear Key", SCRIPT_PARAM_ONOFF, true)
end

function drawingMenu()
        JACdraw = scriptConfig("Just Another Cassiopeia: Draw Settings", "JAC_drawing")
        JACdraw:addParam("DisableDrawing", "Disable All Drawing", SCRIPT_PARAM_ONOFF, false)
        JACdraw:addParam("permaCircle", "Always Draw Range Circles", SCRIPT_PARAM_ONOFF, false)
		JACdraw:addParam("drawQrange", "Draw Q Range Circle", SCRIPT_PARAM_ONOFF, true)
        JACdraw:addParam("drawWrange", "Draw W Range Circle", SCRIPT_PARAM_ONOFF, true)
        JACdraw:addParam("drawErange", "Draw E Range Circle", SCRIPT_PARAM_ONOFF, true)
        JACdraw:addParam("drawRrange", "Draw R Range Circle", SCRIPT_PARAM_ONOFF, true)
        JACdraw:addParam("drawAArange", "Draw AA Range Circle", SCRIPT_PARAM_ONOFF, false)
        JACdraw:addParam("drawtargetcircle", "Draw Circle Around Selected Target", SCRIPT_PARAM_ONOFF, true)
        JACdraw:addParam("drawtarget", "Draw Text on Selected Target", SCRIPT_PARAM_ONOFF, true)
        JACdraw:addParam("drawtargettext", "Draw Target Notification Text", SCRIPT_PARAM_ONOFF, true)
end

function colourMenu()
        JACcol = scriptConfig("Just Another Cassiopeia: Colour Settings", "JAC_colour")
        JACcol:addParam("QColour", "Q Circle Colour", SCRIPT_PARAM_COLOR, {255, 51, 204, 255})
        JACcol:addParam("WColour", "W Circle Colour", SCRIPT_PARAM_COLOR, {255, 204, 51, 255})
        JACcol:addParam("EColour", "E Circle Colour", SCRIPT_PARAM_COLOR, {255, 6, 157, 8})
        JACcol:addParam("RColour", "R Circle Colour", SCRIPT_PARAM_COLOR, {255, 191, 3, 50})
        JACcol:addParam("AAColour", "AA Circle Colour", SCRIPT_PARAM_COLOR, {255, 255, 255, 255})
        JACcol:addParam("targetCircle", "Target Circle Colour", SCRIPT_PARAM_COLOR, {255, 246, 105, 4})
        JACcol:addParam("targetText", "Target Text Colour", SCRIPT_PARAM_COLOR, {255, 246, 105, 4})     
end

function displayMenu()
        JACdisp = scriptConfig("Just Another Cassiopeia: Display Settings", "JAC_display")
        JACdisp:addParam("info", "---- Changes Require Reload ----", SCRIPT_PARAM_INFO, "")
        JACdisp:addParam("disableAll", ">> DISABLE ALL <<", SCRIPT_PARAM_ONOFF, false)
        JACdisp:addParam("combo", "Display Combo Status", SCRIPT_PARAM_ONOFF, true)
        JACdisp:addParam("Harass1", "Display Q+E Harass Status", SCRIPT_PARAM_ONOFF, true)
        JACdisp:addParam("Harass2", "Display Q Harass Status", SCRIPT_PARAM_ONOFF, true)
        JACdisp:addParam("jungleClear", "Display Jungle Clear Status", SCRIPT_PARAM_ONOFF, true)
        JACdisp:addParam("laneClear", "Display Lane Clear Status", SCRIPT_PARAM_ONOFF, true)
        JACdisp:addParam("singleTargetUlt", "Display Single Target Ult Status", SCRIPT_PARAM_ONOFF, true)
        JACdisp:addParam("autoFarm", "Display Auto Farm Status", SCRIPT_PARAM_ONOFF, true)
        JACdisp:addParam("autoE", "Display Auto E Status", SCRIPT_PARAM_ONOFF, true)
--      JACdisp:addParam("autoPT", "Display Passive Tracking Status", SCRIPT_PARAM_ONOFF, true)
end

function permaShow()
    if not JACdisp.disableAll then
        JACmain:permaShow("info")
        if JACdisp.combo then JACmain:permaShow("combo") end
        if JACdisp.Harass1 then JACmain:permaShow("Harass1") end
        if JACdisp.Harass2 then JACmain:permaShow("Harass2") end
        if JACdisp.jungleClear then JACmain:permaShow("jungleClear") end
        if JACdisp.laneClear then JACmain:permaShow("laneClear") end
        if JACdisp.singleTargetUlt then JACult:permaShow("singleTargetUlt") end
        if JACdisp.autoFarm then JACmain:permaShow("autoFarm") end
        if JACdisp.autoE then JACmain:permaShow("autoE") end
--      if JACdisp.autoPT then JACmain:permaShow("autoPT") end
    end
end

function loadClasses()

class 'Colour' -- {
    function Colour.Get(table)
        return ARGB(table[1], table[2], table[3], table[4])
    end
-- }

end

function loadVariables()
        ignite = nil
        enemyHeros = {}
        enemyHerosCount = 0
        NextShot = 0
        aaTime = 0
        minionRange = false
        castRTick = 0
        passiveTick = 0
        igniteTick = 0
        ksDamages = {}
        myTarget = nil
        myHPperc = 0
        targetHPperc = 0
end

function loadSkillData()
    QDelay = 0.7
    WDelay = 0.45
    RDelay = 0.3
    QRangeS = 850
    QRangeL = 925
    WRangeS = 850
    WRangeL = 950
    ERange = 700
    RRange = 775
    AARange = 700
    killRange = 950
end

function loadProdiction()
    wp = ProdictManager.GetInstance()

    tpQ = wp:AddProdictionObject(_Q, QRangeL, 1500, 0.35, 75, myHero,
    function(target, vec1, castspell) 
        if GetDistance(vec1) <= QRangeS then
            CastSpell(_Q, vec1.x, vec1.z)
        elseif GetDistance(vec1) <= QRangeL then
            local vec2 = Vector(player) + (Vector(vec1) - Vector(player)):normalized() * QRangeS
            if vec2 then
                CastSpell(_Q, vec2.x, vec2.z)
            end
        end
    end)
    tpW = wp:AddProdictionObject(_W, WRangeL, 1500, 0.65, 75, myHero, 
    function(target, vec1, castspell)
        if GetDistance(vec1) <= WRangeS then
            CastSpell(_W, vec1.x, vec1.z)
        elseif GetDistance(vec1) <= WRangeL then
            local vec2 = Vector(player) + (Vector(vec1) - Vector(player)):normalized() * WRangeS
            if vec2 then
                CastSpell(_W, vec2.x, vec2.z)
            end
        end
    end)
    tpR = TargetPredictionVIP(RRange, 1000, RDelay)
	tpR1 = wp:AddProdictionObject(_R, RRange, 1000, RDelay, 0, myHero, 
	function(target, vec, castspell)
		enemyPos = tpR1:GetPrediction(target)
		if enemyPos ~= nil then 
			if GetDistance(enemyPos) <= GetDistance(target) and ValidTarget(target, RRange) and GetDistance(vec) <= RRange * 3/4 and GetDistance(vec) > RRange * 1/4 then
				CastSpell(_R, vec.x, vec.z)
			end
		end
	end)
end

function loadVIPPrediction()
		tpQmob = TargetPredictionVIP(rangeQ, math.huge, 0.7)
		tpWmob = TargetPredictionVIP(rangeW, math.huge, 0.45)
end

function loadMinions()
		enemyMinion = minionManager(MINION_ENEMY, 1000, player, MINION_SORT_HEALTH_DSC)
        jungleMinion = minionManager(MINION_JUNGLE, 1000, player, MINION_SORT_HEALTH_DSC)
--      allyMinions = minionManager(MINION_ALLY, 2000, player, MINION_SORT_HEALTH_DSC)
end

function loadSpells()
        if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then 
                ignite = SUMMONER_1
        elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
                ignite = SUMMONER_2
        else 
                ignite = nil
        end
end

function loadEnemies()
        for i = 1, heroManager.iCount do
                local hero = heroManager:GetHero(i)
                if hero.team ~= player.team then
                        local enemyCount = enemyHerosCount + 1
                        enemyHeros[enemyCount] = {object = hero, waittxt = 0, killable = 0 }
                        enemyHerosCount = enemyCount
                end
        end
end

function OnTick()
        if not myHero.dead then
                QREADY = (myHero:CanUseSpell(_Q) == READY)
                WREADY = (myHero:CanUseSpell(_W) == READY)
                EREADY = (myHero:CanUseSpell(_E) == READY)
                RREADY = (myHero:CanUseSpell(_R) == READY)
                IREADY = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
                
                checkKillRange()
                runTarget()
                orbWalking()
                HPcheck()
                useUlt()
                
                if JACmain.autoFarm and not JACmain.combo and not JACult.castUlt and not JACmain.Harass1 and not JACmain.Harass2 then
                        autoFarm()
                end

                if JACmain.jungleClear and not JACmain.combo and not JACult.castUlt and not JACmain.Harass1 and not JACmain.Harass2 and not JACmain.laneClear then
                        jungleClear()
                end
                
                if JACmain.laneClear and not JACmain.combo and not JACult.castUlt and not JACmain.Harass1 and not JACmain.Harass2 and not JACmain.jungleClear then
                        laneClear()
                end
                
--              if JACmain.combo then
--                      Combo()
--              end             
                
                if JACmain.Harass1 and not JACmain.combo then
                        Harass1()
                end
                
                if JACmain.Harass2 and not JACmain.combo then
                        Harass2()
                end
                
                if JACult.castUlt then
                        CastR1(myTarget)
                end
                
        end
end

function HPcheck()
        if ValidTarget(myTarget, killRange) and RREADY then
                myHPperc = (myHero.health / myHero.maxHealth * 100)
                targetHPperc = (myTarget.health / myTarget.maxHealth * 100)
        end
end

function checkKillRange()
        if WREADY then
                killRange = 950
        elseif QREADY then
                killRange = 925
        else
                killRange = 750
        end
end

function Target()
        local currentTarget = nil
        local killMana = 0
        if ValidTarget(myTarget) then
                if GetDistance(myTarget)>killRange then
                        myTarget = nil
                end
        else
                myTarget = nil
        end
        for i = 1, enemyHerosCount do
                local Enemy = enemyHeros[i].object
                if ValidTarget(Enemy) then
                        local pdmg = getDmg("P", Enemy, myHero, 3)
                        local qdmg = getDmg("Q", Enemy, myHero, 3)
                        local wdmg = getDmg("W", Enemy, myHero, 3)
                        local edmg = getDmg("E", Enemy, myHero, 3)
                        local rdmg = getDmg("R", Enemy, myHero, 3)
                        local ADdmg = getDmg("AD", Enemy, myHero, 3)
                        local dfgdamage = (GetInventoryItemIsCastable(3128) and getDmg("DFG",Enemy,myHero) or 0) -- Deathfire Grasp
                        local hxgdamage = (GetInventoryItemIsCastable(3146) and getDmg("HXG",Enemy,myHero) or 0) -- Hextech Gunblade
                        local bwcdamage = (GetInventoryItemIsCastable(3144) and getDmg("BWC",Enemy,myHero) or 0) -- Bilgewater Cutlass
                        local botrkdamage = (GetInventoryItemIsCastable(3153) and getDmg("RUINEDKING", Enemy, myHero) or 0) --Blade of the Ruined King
                        local onhitdmg = (GetInventoryHaveItem(3057) and getDmg("SHEEN",Enemy,myHero) or 0) + (GetInventoryHaveItem(3078) and getDmg("TRINITY",Enemy,myHero) or 0) + (GetInventoryHaveItem(3100) and getDmg("LICHBANE",Enemy,myHero) or 0) + (GetInventoryHaveItem(3025) and getDmg("ICEBORN",Enemy,myHero) or 0) + (GetInventoryHaveItem(3087) and getDmg("STATIKK",Enemy,myHero) or 0) + (GetInventoryHaveItem(3209) and getDmg("SPIRITLIZARD",Enemy,myHero) or 0)
                        local onspelldamage = (GetInventoryHaveItem(3151) and getDmg("LIANDRYS",Enemy,myHero) or 0) + (GetInventoryHaveItem(3188) and getDmg("BLACKFIRE",Enemy,myHero) or 0)
                        local sunfiredamage = (GetInventoryHaveItem(3068) and getDmg("SUNFIRE",Enemy,myHero) or 0)
                        local comboKiller = pdmg + qdmg + wdmg + edmg + rdmg + onhitdmg + onspelldamage + sunfiredamage + hxgdamage + bwcdamage + botrkdamage
                        local killHim = pdmg + onhitdmg + onspelldamage + sunfiredamage + hxgdamage + bwcdamage + botrkdamage
                        if IREADY then
                                local idmg = getDmg("IGNITE",Enemy,myHero, 3)
                                comboKiller = comboKiller + idmg
                                killHim = killHim + idmg
                                if GetDistance(Enemy)< 600 then
                                        if idmg>=Enemy.health then
                                                CastSpell(ignite, Enemy)
                                        end
                                end
                        end
                        if QREADY then        
                                killMana = killMana + myHero:GetSpellData(_Q).mana
                                if GetDistance(Enemy)<=QRangeL then
                                        killHim = killHim + qdmg
                                        if qdmg >=Enemy.health and not IsIgnited() then
                                                table.insert(ksDamages, qdmg)
                                                ksQDmg = qdmg
                                        end
                                end
                        end
                        if WREADY then
                                killMana = killMana + myHero:GetSpellData(_W).mana        
                                if GetDistance(Enemy)<=WRangeL then
                                        killHim = killHim + wdmg
                                        if wdmg >=Enemy.health and not IsIgnited() then
                                                table.insert(ksDamages, wdmg)
                                                ksWDmg = wdmg
                                        end
                                end
                        end
                        if EREADY then
                                killMana = killMana + myHero:GetSpellData(_E).mana
                                if GetDistance(Enemy)<=ERange then
                                        killHim = killHim + edmg
                                        if edmg>=Enemy.health and not IsIgnited() then
                                                table.insert(ksDamages, edmg)
                                                ksEDmg = edmg
                                        end
                                end
                        end
                        if RREADY then
                                killMana = killMana + myHero:GetSpellData(_R).mana
                                if GetDistance(Enemy)<=RRange then
                                        killHim = killHim + rdmg
                                        if rdmg>=Enemy.health and not IsIgnited()and JACult.ultKS then
                                                table.insert(ksDamages, rdmg)
                                                ksRDmg = rdmg
                                        end
                                end
                        end
                        if next(ksDamages)~=nil then
                                table.sort(ksDamages, function (a, b) return a<b end)
                                local lowestKSDmg = ksDamages[1]
                                if qdmg == lowestKSDmg then
                                        CastQ(Enemy)
                                elseif wdmg == lowestKSDmg then
                                        CastW(Enemy)
                                elseif edmg == lowestKSDmg then
                                        CastE(Enemy)
                                elseif rdmg == lowestKSDmg then
                                        if JACult.ultKS then
                                                CastR(1)
                                        end
                                end
                                table.clear(ksDamages)
                        end
                        if GetInventoryItemIsCastable(3128) then  -- DFG      
                                comboKiller = comboKiller + dfgdamage + (comboKiller*0.2)
                                killHim = killHim + dfgdamage + (killHim*0.2) 
                                if GetInventoryItemIsCastable(3146) then -- Hxg
                                        comboKiller = comboKiller + (hxgdamage*0.2)
                                        killHim = killHim + (hxgdamage*0.2)
                                end
                                if GetInventoryItemIsCastable(3144) then -- bwc
                                        comboKiller = comboKiller + (bwcdamage*0.2)
                                        killHim = killHim + (bwcdamage*0.2)
                                end
                                if GetInventoryItemIsCastable(3153) then -- botrk
                                        comboKiller = comboKiller + (botrkdamage*0.2)
                                        killHim = killHim + (botrkdamage*0.2)
                                end
                        end
                        currentTarget = Enemy
                        if killHim >= currentTarget.health and killMana<= myHero.mana then
                                enemyHeros[i].killable = 3
                                if GetDistance(currentTarget) <= killRange then
                                        if myTarget == nil then
                                                myTarget = currentTarget
                                        elseif GetDistance(myHero, myTarget) > GetDistance(myHero, currentTarget) then
                                                myTarget = currentTarget
                                        end
                                        if ValidTarget(myTarget) then
                                                killTarget(myTarget)
                                        end
                                end
                        elseif comboKiller >= currentTarget.health then
                                enemyHeros[i].killable = 2
                                if GetDistance(currentTarget) <= killRange then
                                        if myTarget == nil then
                                                myTarget = currentTarget
                                        elseif GetDistance(myHero, myTarget) > GetDistance(myHero, currentTarget) then
                                                myTarget = currentTarget
                                        end
                                        if ValidTarget(myTarget) then
                                                comboTarget(myTarget)
                                        end
                                end
                        else
                                enemyHeros[i].killable = 1
                                if GetDistance(currentTarget) <= killRange then
                                        if myTarget == nil then
                                                myTarget = currentTarget
                                        elseif GetDistance(myHero, myTarget) > GetDistance(myHero, currentTarget) then
                                                myTarget = currentTarget
                                        end
                                        if ValidTarget(myTarget) then
                                                harassTarget(myTarget)
                                        end
                                end        
                        end
                else
                        killable = 0
                end
        end
end

function runTarget()
        Target()
end

function IsIgnited(target)
        if TargetHaveBuff("SummonerDot", target) then
                igniteTick = GetTickCount()
                return true
        elseif igniteTick == nil or GetTickCount()-igniteTick>500 then
                return false
        end
end

function autoFarm()
        enemyMinion:update()
        if next(enemyMinion.objects)~= nil then
                for j, minion in pairs(enemyMinion.objects) do
                        if minion.valid then
                        local edamage = getDmg("E", minion, myHero, 3)
                                if edamage>=minion.health then
                                        CastE(minion)
                                end
                        end
                end
        end
end

function jungleClear()
        jungleMinion:update()
        if next(jungleMinion.objects)~= nil then
                for j, minion in pairs(jungleMinion.objects) do
                        if minion.valid then
                                if not TargetHaveBuff("cassiopeianoxiousblastpoison", minion) then
                                        CastQmob(minion)
                                end
                                if not TargetHaveBuff("cassiopeiamiasmapoison", minion) then
                                        CastWmob(minion)
                                end
                                CastE(minion)
                        end
                end
        end
end

function laneClear()
	enemyMinion:update()
	if next(enemyMinion.objects)~= nil then
		for j, minion in pairs(enemyMinion.objects) do
			if minion.valid then
				if not TargetHaveBuff("cassiopeianoxiousblastpoison", minion) and not TargetHaveBuff("cassiopeiamiasmapoison", minion) then
					CastQmob(minion)
				end
				if not TargetHaveBuff("cassiopeiamiasmapoison", minion) and not TargetHaveBuff("cassiopeianoxiousblastpoison", minion) then
					CastWmob(minion)
				end
				local edamage = getDmg("E", minion, myHero, 3)
				if edamage>=minion.health then
					CastE(minion)
				end
			end
		end
	end
end

function useUlt()
        if GetTickCount()-castRTick >= 101 then
                castRTick = GetTickCount()
                if JACmain.combo then
                        if JACult.setUltEnemiesSBTW > 0 then
                                CastR(JACult.setUltEnemiesSBTW)
                                if JACult.singleTargetUlt then
                                        if not JACult.ignoreHP then
                                                if myHPperc >= JACult.myMaxHP and myHPperc <= JACult.myMinHP and targetHPperc >= JACult.targetMinHP and targetHPperc <= JACult.targetMaxHP then
                                                        if (myHPperc / targetHPperc) * 100 >= JACult.minHPratio and (myHPperc / targetHPperc) * 100 <= JACult.maxHPratio * 100 then
                                                                CastR(1)
                                                        end
                                                end
                                        else
                                                CastR(1)
                                        end
                                end
                        end
                end
                if JACult.setUltEnemiesAUTO > 0 then
                        CastR(JACult.setUltEnemiesAUTO)
                end
        end
end

function killTarget(target)
        if ValidTarget(target) and not IsIgnited() then
                if JACmain.autoE then 
                        CastE(target)
                else
                        if JACmain.combo then
                                CastE(target)
                        end
                end
                if JACmain.combo then
                        if JACmain.useItems then
                                CastItems(myTarget, true)
                        end
                        CastQ(target)
                        CastW(target)
                end
                if JACult.castUlt then
                        CastR1(myTarget)
                end
        end
end

function comboTarget(target)
        if ValidTarget(target) then
                if JACmain.autoE then 
                        CastE(target)
                else
                        if JACmain.combo then
                                CastE(target)
                        end
                end
                if JACmain.combo then
                        if JACmain.useItems then
                                CastItems(myTarget, true)
                        end
                        CastQ(target)
                        CastW(target)
                end
                if JACult.castUlt then
                        CastR1(myTarget)
                end
        end
end

function harassTarget(target)
        if ValidTarget(target) then
                if JACmain.autoE then 
                        CastE(target)
                else
                        if JACmain.combo then
                                CastE(target)
                        end
                end
                if JACmain.combo then
                        if JACmain.useItems then
                                CastItems(myTarget, true)
                        end
                        CastQ(target)
                        CastW(target)
                end
                if JACult.castUlt then
                        CastR1(myTarget)
                end
        end
end

function Combo()
        if ValidTarget(myTarget) then
                if JACmain.autoE then 
                        CastE(myTarget)
                else
                        if JACmain.combo then
                                CastE(myTarget)
                        end
                end
                if JACmain.combo then
                        if JACmain.useItems then
                                CastItems(myTarget, true)
                        end
                        CastQ(myTarget)
                        CastW(myTarget)
                end
                if JACult.castUlt then
                        CastR1(myTarget)
                end
        end
end

function Harass1()
        if ValidTarget(myTarget) then
                if JACmain.autoE then 
                        CastE(myTarget)
                else
                        if JACmain.Harass1 then
                                CastE(myTarget)
                        end
                end
                if JACmain.Harass1 then
                        CastQ(myTarget)
                end
                if JACult.castUlt then
                        CastR1(myTarget)
                end
        end
end

function Harass2()
        if ValidTarget(myTarget) then
                if JACmain.Harass2 then
                        CastQ(myTarget)
                end
                if JACult.castUlt then
                        CastR1(myTarget)
                end
        end
end

function CastQmob(target)
	if ValidTarget(target) then
		if GetDistance(target) <= QRangeL and QREADY then
			local QPos = tpQmob:GetPrediction(target)
			if QPos and GetDistance(QPos)<=QRangeL then
				CastSpell(_Q, QPos.x, QPos.z)
			end
		end
	else
		return
	end
end

function CastWmob(target)
	if ValidTarget(target) then
		if GetDistance(target) <= WRangeL and WREADY then
			local WPos = tpWmob:GetPrediction(target)
			if WPos and GetDistance(WPos)<=WRangeL then
				CastSpell(_W, WPos.x, WPos.z)
			end
		end
	else
		return
	end
end

function CastQ(target)
	if ValidTarget(target) and QREADY then
		tpQ:EnableTarget(target, true)
		return true
	end
	return false
end

function CastW(target)
	if ValidTarget(target) and WREADY then
		tpW:EnableTarget(target, true)
		return true
	end
	return false
end

function CastE(target)
	if ValidTarget(target) then
		if GetDistance(target) <= ERange and EREADY then
			if TargetHaveBuff("cassiopeianoxiousblastpoison", target) or TargetHaveBuff("cassiopeiamiasmapoison", target) or TargetHaveBuff("toxicshotparticle", target) or TargetHaveBuff("bantamtraptarget", target) or TargetHaveBuff("poisontrailtarget", target) or TargetHaveBuff("deadlyvenom", target) then
				CastSpell(_E, target)
			end
		end
	else
		return
	end
end

function CastR1(target)
	if ValidTarget(target) and RREADY then
		tpR1:EnableTarget(target, true)
	end
end

function CastR(n)
	if RREADY then
		local RCount = CountEnemyHeroInRange(RRange + 300)
		if RCount >= n then
			local vec = GetCassMECS(75, RRange, n, false)
			if vec ~= nil and GetDistance(vec) < RRange then
				CastSpell(_R, vec.x, vec.z)
			end
		end
	end
end

function CastItems(target, allItems)
        if not ValidTarget(target) then 
                return
        else
                if GetDistance(target) <=800 and allItems == true then
                        CastItem(3144, target) --Bilgewater Cutlass
                        CastItem(3153, target) --Blade Of The Ruin King
                        CastItem(3128, target) --Deathfire Grasp
                        CastItem(3146, target) --Hextech Gunblade
                        CastItem(3188, target) --Blackfire Torch  
                end
                if GetDistance(target) <= 275 then
                        CastItem(3184, target) --Entropy
                        CastItem(3143, target) --Randuin's Omen
                        CastItem(3074, target) --Ravenous Hydra
                        CastItem(3131, target) --Sword of the Devine
                        CastItem(3077, target) --Tiamat
                        CastItem(3142, target) --Youmuu's Ghostblade
                end
                if GetDistance(target) <= 1000 then
                        CastItem(3023, target) --Twin Shadows
                end
        end
end

function orbWalking()                
	if GetTickCount() > NextShot then
		if ValidTarget(myTarget) then
			if GetDistance(myTarget)<=myHero.range +70 then
				if JACmain.combo and JACaa.aaCombo then
					myHero:Attack(myTarget)
				end
				if JACmain.Harass1 and JACaa.aaHarass1 then
					myHero:Attack(myTarget)
				end
				if JACmain.Harass2 and JACaa.aaHarass2 then
					myHero:Attack(myTarget)
				end
			else
				if JACmain.combo and JACmtm.mtmCombo then
					myHero:MoveTo(mousePos.x, mousePos.z)
				end
				if JACmain.Harass1 and JACmtm.mtmHarass1 then
					myHero:MoveTo(mousePos.x, mousePos.z)
				end
				if JACmain.Harass2 and JACmtm.mtmHarass2 then
					myHero:MoveTo(mousePos.x, mousePos.z)
				end
			end
		elseif not JACmain.combo and not JACult.castUlt and not JACmain.Harass1 and not JACmain.Harass2 then
			minionRange = false
			enemyMinion:update()
			jungleMinion:update()
			for i, minion in pairs(enemyMinion.objects) do
				if minion.valid then
					if GetDistance(minion)<=myHero.range+70 and JACmain.laneClear then
						myHero:Attack(minion)
						minionRange = true
					else
						minionRange = false
					end
				end
			end
			for j, minion in pairs(jungleMinion.objects) do
				if minion.valid then
					if GetDistance(minion)<=myHero.range+70 and JACmain.jungleClear then
						myHero:Attack(minion)
						minionRange = true
					else
						minionRange = false
					end
				end
			end
		end
		if not minionRange and not ValidTarget(myTarget) then
			if JACmtm.mtmCombo and JACmain.combo then
				myHero:MoveTo(mousePos.x, mousePos.z)
			end
			if JACmtm.mtmHarass1 and JACmain.Harass1 then
				myHero:MoveTo(mousePos.x, mousePos.z)
			end
			if JACmtm.mtmHarass2 and JACmain.Harass2 then
				myHero:MoveTo(mousePos.x, mousePos.z)
			end
		end
	elseif GetTickCount() > aaTime then
		if JACmain.combo and JACmtm.mtmCombo then
			myHero:MoveTo(mousePos.x, mousePos.z)
		end
		if JACmain.Harass1 and JACmtm.mtmHarass1 then
			myHero:MoveTo(mousePos.x, mousePos.z)
		end
		if JACmain.Harass2 and JACmtm.mtmHarass2 then
			myHero:MoveTo(mousePos.x, mousePos.z)
		end
	end
end

function OnProcessSpell(unit, spell)
        if unit.isMe and spell.name:lower():find("attack") and spell.animationTime then
                aaTime = GetTickCount() + spell.windUpTime * 1000 - GetLatency() / 2 + 10 + 50
                NextShot = GetTickCount() + spell.animationTime * 1000
        end
end

function OnDrawRanges()
        if JACdraw.drawQrange and player:CanUseSpell(_Q) == READY then
                DrawCircle(player.x, player.y, player.z, QRangeL, Colour.Get(JACcol.QColour))
        end
        if JACdraw.drawWrange and player:CanUseSpell(_W) == READY then
                DrawCircle(player.x, player.y, player.z, WRangeL, Colour.Get(JACcol.WColour))
        end
        if JACdraw.drawErange and player:CanUseSpell(_E) == READY then
                DrawCircle(player.x, player.y, player.z, ERange, Colour.Get(JACcol.EColour))
        end
        if JACdraw.drawRrange and player:CanUseSpell(_R) == READY then
                DrawCircle(player.x, player.y, player.z, RRange, Colour.Get(JACcol.RColour))
        end
        if JACdraw.drawAArange then
                DrawCircle(player.x, player.y, player.z, AARange, Colour.Get(JACcol.AAColour))
        end
end

function PermaDrawRanges()
        if JACdraw.drawQrange then
                DrawCircle(player.x, player.y, player.z, QRangeL, Colour.Get(JACcol.QColour))
        end
        if JACdraw.drawWrange then
                DrawCircle(player.x, player.y, player.z, WRangeL, Colour.Get(JACcol.WColour))
        end
        if JACdraw.drawErange then
                DrawCircle(player.x, player.y, player.z, ERange, Colour.Get(JACcol.EColour))
        end
        if JACdraw.drawRrange then
                DrawCircle(player.x, player.y, player.z, RRange, Colour.Get(JACcol.RColour))
        end
        if JACdraw.drawAArange then
                DrawCircle(player.x, player.y, player.z, AARange, Colour.Get(JACcol.AAColour))
        end
end

function OnDrawTarget()
        if JACdraw.drawtargetcircle then
                if ValidTarget(myTarget) then
                        DrawCircle(myTarget.x, myTarget.y, myTarget.z, 100, Colour.Get(JACcol.targetCircle))
                end
        end
        if JACdraw.drawtargettext then
                if ValidTarget(myTarget) then
                        DrawText3D("Targetting: " .. myTarget.charName, player.x, player.y, player.z, 16, Colour.Get(JACcol.targetText), true)
                end
    end
        if JACdraw.drawtarget then
                if ValidTarget(myTarget) then
                        DrawText3D("TARGET", myTarget.x, myTarget.y, myTarget.z, 16, Colour.Get(JACcol.targetText), true)
                end
    end
--      if ValidTarget(myTarget) then
--              DrawArrows(myHero, newTarget, 30, 0x099B2299, 50)
--      end
        for i = 1, enemyHerosCount do
                local Enemy = enemyHeros[i].object
                local killable = enemyHeros[i].killable
                if ValidTarget(Enemy) then
                        if killable == 4 then
                                DrawText3D(tostring("Kill Steal"), Enemy.x, (Enemy.y - 70), Enemy.z, 16, ARGB(255,255,10,20), true)
                        elseif killable == 3 then
                                DrawText3D(tostring("Killable"), Enemy.x, (Enemy.y - 70), Enemy.z, 16, ARGB(255,255,143,20), true)
                        elseif killable == 2 then
                                DrawText3D(tostring("Combo Killer"), Enemy.x, (Enemy.y - 70), Enemy.z, 16, ARGB(255,248,255,20), true) 
                        elseif killable == 1 then
                                DrawText3D(tostring("Harass Him"), Enemy.x, (Enemy.y - 70), Enemy.z, 16, ARGB(255,10,255,20), true)
                        else
                                DrawText3D(tostring("Not Killable"), Enemy.x, (Enemy.y - 70), Enemy.z, 16, ARGB(244,66,155,255), true)
                        end
                end
        end 
end

function OnDraw()
        if not myHero.dead then
                if not JACdraw.DisableDrawing and not JACdraw.permaCircle then
                        OnDrawRanges()
                        OnDrawTarget()
                end
                if not JACdraw.DisableDrawing and JACdraw.permaCircle then
                        PermaDrawRanges()
                        OnDrawTarget()
                end
        end
end

-------------------- iLib --------------------

function areClockwise(testv1,testv2)
        return -testv1.x * testv2.y + testv1.y * testv2.x>0 --true if v1 is clockwise to v2
end

function sign(x)
        if x> 0 then return 1
        elseif x<0 then return -1
        end
end

local mecCheckTick = 0

function GetCassMECS(theta, radius, minimum, bForce)
        if GetTickCount() - mecCheckTick < 100 then return nil end

    mecCheckTick = GetTickCount() 
        
--- Build table of enemies in range
        nFaced = 0
        n = 1
        v1,v2,v3 = 0,0,0
        largeN,largeV1,largeV2 = 0,0,0
        theta1,theta2,smallBisect = 0,0,0
        coneTargetsTable = {}

        for i = 1, heroManager.iCount, 1 do
                hero = heroManager:getHero(i)
                enemyPos = tpR:GetPrediction(hero)
                if ValidTarget(hero, 1000) and enemyPos and GetDistance(enemyPos) < radius then -- and inRadius(hero,radius*radius) then
                        coneTargetsTable[n] = hero
                        n=n+1
					if hero.visionPos ~= nil then
                        if (GetDistance(hero.visionPos) < GetDistance(hero)) 
                                or killable == 4
                                or killable == 3 
                                or bForce == true then
                                nFaced = nFaced + 1
                        else
                                nFaced = nFaced + 0.67
                        end
					end
				end
        end

        if #coneTargetsTable>=2 then -- true if calculation is needed --Determine if angle between vectors are < given theta
                for i=1, #coneTargetsTable,1 do
                        for j=1,#coneTargetsTable, 1 do
                                if i~=j then
                                        -- Position vector from player to 2 different targets.
                                        v1 = Vector(coneTargetsTable[i].x-player.x , coneTargetsTable[i].z-player.z)
                                        v2 = Vector(coneTargetsTable[j].x-player.x , coneTargetsTable[j].z-player.z)
                                        thetav1 = sign(v1.y)*90-math.deg(math.atan(v1.x/v1.y))
                                        thetav2 = sign(v2.y)*90-math.deg(math.atan(v2.x/v2.y))
                                        thetaBetween = thetav2-thetav1                 

                                        if (thetaBetween) <= theta and thetaBetween>0 then -- true if targets are close enough together.
                                                if #coneTargetsTable == 2 then -- only 2 targets, the result is found.
                                                        largeV1 = v1
                                                        largeV2 = v2
                                                else
                                                        -- Determine # of vectors between v1 and v2                                                     
                                                        tempN = 0
                                                        for k=1, #coneTargetsTable,1 do
                                                                if k~=i and k~=j then
                                                                        -- Build position vector of third target
                                                                        v3 = Vector(coneTargetsTable[k].x-player.x , coneTargetsTable[k].z-player.z)
                                                                        -- For v3 to be between v1 and v2
                                                                        -- it must be clockwise to v1
                                                                        -- and counter-clockwise to v2
                                                                        if areClockwise(v3,v1) and not areClockwise(v3,v2) then
                                                                                tempN = tempN+1
                                                                        end
                                                                end
                                                        end
                                                        if tempN > largeN then
                                                        -- store the largest number of contained enemies
                                                        -- and the bounding position vectors
                                                                largeN = tempN
                                                                largeV1 = v1
                                                                largeV2 = v2
                                                        end
                                                end
                                        end
                                end
                        end
                end
        elseif #coneTargetsTable==1 and minimum == 1 then
                return coneTargetsTable[1]
        end
   
        if largeV1 == 0 or largeV2 == 0 then
        -- No targets or one target was found.
                return nil
        else
                -- small-Bisect the two vectors that encompass the most vectors.
                if largeV1.y == 0 then
                        theta1 = 0
                else
                        theta1 = sign(largeV1.y)*90-math.deg(math.atan(largeV1.x/largeV1.y))
                end
                if largeV2.y == 0 then
                        theta2 = 0
                else
                        theta2 = sign(largeV2.y)*90-math.deg(math.atan(largeV2.x/largeV2.y))
                end

                smallBisect = math.rad((theta1 + theta2) / 2)
                vResult = {}
                vResult.x = radius*math.cos(smallBisect)+player.x
                vResult.y = player.y
                vResult.z = radius*math.sin(smallBisect)+player.z
            
                return vResult
        end
end

-------------------- iLib --------------------