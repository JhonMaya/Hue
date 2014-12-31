<?php exit() ?>--by Kain 97.90.203.108
--[[
    Trackeee 2 beta
    by Weee
    
    A lot of ideas and inspiration from a lot of people from BoL community.
    Design ideas: farissi, dansa, and many others from Trackeee contest: http://botoflegends.com/forum/topic/5765-contest-best-uiconcept-for-trackeee/

    If you like this work and you want to donate - I'll be glad to accept skin codes for following champs (EUNE):
        Riot Singed
        K9 Nasus
        PAX TF
        Arcade Hecarim
        Full Metal Rammus

        Thanks :3


    TODO:

    Testing (done but needs more testing):
    -- VIP checks (so some VIP functions won't be loaded for free users)
    -- [Waiteee] Test it. Add Zac.

    Important (what next to do?):
    -- Power Auras analogue? Or new spell state "ACTIVE" ?
    -- Include Goldeee?
    -- Simple side UI:
            pop-up notifications:
                how many minions till next level
                what you can buy now
            circular cooldown bar for allies (mostly done, but needs proper order, like scoreboard order or something).
    -- Check if enemy got buffs from items but he does not have these items (it means somebody is staying nearby him in the bushes).
            
    
    Not important (someday later):
    -- include current bounties/worth of enemies and/or allies?
    -- add auto-reminder if there is an update available
    -- self.supressed = self.hero.isMe and self.hero:CanUseSpell(self.key) == SUPRESSED  is there a problem with PASSIVE state for other champs?
    -- ?????????? ????? ???????? ?? ?????. ? ??????? ???????????? ??????? ????????????(??????????? ????? ??????).
       ????? ?????? ????????? ???? ????? WAYPOINT MANAGER. ????? ???? ???? ??????? ??????? - ?????? ?????? progressbar ?????????? ??????, ? ?????????? ???????? ????????.
    -- add more cooler presets? any feedback?
    -- [COMPACT] if you can survive by using shield/barrier - then show yellow or beige "BARRIER"
    -- Look into buff.types from Klokje lib
    -- [COMPACT] pink wards counter
    -- [DETAILED] execute timer (countdown from last enemy debuff/hit. tells you how much time to wait before executing to prevent enemy from getting the kill)
    -- [COMPACT] Add more display name modes: CC, role, more ideas?
    -- [COMPACT] tower hits bothways
    -- Use LevelTextOffset to hide level and replace it with something
    -- Draw blinking sprite animations when spell becomes ready (Farissi pls)
    -- maybe different text color for CC spells
    -- Armageddon: @Weee I actually had a pretty good idea. Minions left for next level up.
                   Example 3 Meele minions till next minion OR 3 Ranged minions till next level.
                   This can help alot in lane , and to know when your about to hit 6 to all in.
]]

local floor, ceil, max, min = math.floor, math.ceil, math.max, math.min
local INRANGE, DANGER, ACTIVE = 6, 7, 8
local consumablesTable = {
    [2003] = true,
    [2004] = true,
    [2047] = true,
    [2041] = true,
    [2044] = true,
    [2043] = true,
    [2039] = true,
    [2037] = true,
    [2042] = true,
    [2049] = true,
    [2045] = true,
}
local summonersTable = {
    ["SummonerBarrier"] = "Barrier",        ["SummonerExhaust"] = "Exhaust",    ["SummonerDot"] = "Ignite",
    ["SummonerFlash"] = "Flash",            ["SummonerHaste"] = "Ghost",        ["SummonerHeal"] = "Heal",
    ["SummonerMana"] = "Clarity",           ["SummonerTeleport"] = "TP",        ["SummonerRevive"] = "Revive",
    ["SummonerClairvoyance"] = "CV",        ["SummonerSmite"] = "Smite",        ["SummonerOdinGarrison"] = "Garrison",
    ["SummonerBoost"] = "Cleanse"
}
local itemTable = {
    [3187] = "Sweeper",         --Hextech Sweeper
    [3060] = "Banner",          --Banner of Command
    [3188] = "Blackfire",       --Blackfire Torch
    [3157] = "Zhonya",          --Zhonya's Hourglass
    [3190] = "Locket",          --Locket of the Iron Solari
    [3222] = "Mikael",          --Mikael's Crucible
    [3159] = "Grez",            --Grez's Spectral Lantern
    [3131] = "SoTD",            --Sword of the Divine
    [3069] = "Shurelya",        --Shurelya's Reverie
    [2051] = "Horn",            --Guardian's Horn
    [3040] = "Seraph",          --Seraph's Embrace
    [3139] = "QSS",             --Mercurial Scimitar
    [3140] = "QSS",             --Quicksilver Sash
    [3077] = "Tiamat",          --Tiamat (Melee Only)
    [3142] = "Youmuu",          --Youmuu's Ghostblade
    [3146] = "Gunblade",        --Hextech Gunblade
    [3144] = "Cutlass",         --Bilgewater Cutlass
    [3092] = "True Ice",        --Shard of True Ice
    [3143] = "Randiun",         --Randuin's Omen
    [3180] = "Odyn",            --Odyn's Veil
    [3023] = "TwinS",           --Twin Shadows
    [3128] = "DFG",             --Deathfire Grasp
    [3056] = "Ohm",             --Ohmwrecker
    [3184] = "Entropy",         --Entropy
    [3074] = "Hydra",           --Ravenous Hydra (Melee Only)
    [3153] = "BotRK",           --Blade of the Ruined King
    [3185] = "Lightbringer",    --The Lightbringer
    [3090] = "Witchcap",        --Wooglet's Witchcap
    [3154] = "Lantern",         --Wriggle's Lantern
    [3026] = "GA",              --Guardian Angel
    [3155] = "Hexdrinker",      --Hexdrinker
    [3124] = "Guinsoo",         --Guinsoo's Rageblade
    [3156] = "Maw",             --Maw of Malmortius
}
local passiveTable = {
    Anivia = "rebirthcooldown",
    Aatrox = "aatroxpassiveactivate",
    Blitzcrank = "manabarriercooldown",
    Vi = "vipassivebuff",
    Volibear = "volibearpassivecd",
    Zac = "zacrebirthcooldown",
}
local immuneTable = {
    { "zhonyas_ring_activate.troy", 2.5 },              -- Zhonya
    { "Aatrox_Passive_Death_Activate.troy", 3 },        -- Aatrox Passive
    { "LifeAura.troy", 4 },                             -- GA and Zil Ulti after death
    { "nickoftime_tar.troy", 7 },                       -- Zil Ulti before death
    { "eyeforaneye_self.troy", 2 },                     -- Kayle Ulti (self?)
    { "UndyingRage_buf.troy", 5 },                      -- Tryn Ulti
    { "EggTimer.troy", 6 },                             -- Anivia Egg
}
local inRangeExceptions = {
    Aatrox = { [_W] = true },
    Ashe = { [_E] = true },
    Akali = { [_W] = true },
    Alistar = { [_E] = true },
    Anivia = { [_W] = true },
    Blitzcrank = { [_W] = true },
    Caitlyn = { [_W] = true },
    DrMundo = { [_E] = true },
    Gangplank = { [_E] = true },
    Graves = { [_E] = true },
    Janna = { [_E] = true },
    Karma = { [_E] = true },
    Karthus = { [_W] = true },
    Kayle = { [_W] = true },
    LeeSin = { [_W] = true },
    Lulu = { [_R] = true },
    Mordekaiser = { [_W] = true },
    Morgana = { [_E] = true },
    Nami = { [_E] = true },
    Nidalee = { [_W] = true, [_E] = true },
    Nunu = { [_Q] = true, [_W] = true },
    Orianna = { [_E] = true },
    Rumble = { [_W] = true },
    Shaco = { [_W] = true },
    Shen = { [_W] = true, [_R] = true },
    Singed = { [_Q] = true },
    Sivir = { [_R] = true },
    Sona = { [_W] = true, [_E] = true },
    Soraka = { [_W] = true, [_R] = true },
    Taric = { [_Q] = true },
    Teemo = { [_R] = true },
    Thresh = { [_W] = true },
    Trundle = { [_W] = true, [_E] = true },
    Lux = { [_W] = true },
    Warwick = { [_E] = true },
    Zyra = { [_W] = true },
}
local lvlTable = {
    [0] = 0,        [1] = 280,      [2] = 670,      [3] = 1170,     [4] = 1780,     [5] = 2500,
    [6] = 3330,     [7] = 4270,     [8] = 5320,     [9] = 6480,     [10] = 7750,    [11] = 9130,
    [12] = 10620,   [13] = 12220,   [14] = 13930,   [15] = 15750,   [16] = 17680,   [17] = 19720
}
local layoutPresets = {
    {       -- Default Trackeee
        onTop = false,
        bgHeight = 2,
        qwerWidth = 23,
        qwerHeight = 5,
        qwerOffset = 0,
        timerOffset = 5,
    },
    {       -- Farissi 1
        onTop = false,
        bgHeight = 9,
        qwerWidth = 23,
        qwerHeight = 5,
        qwerOffset = 0,
        timerOffset = 7,
    },
    {       -- Farissi 2
        onTop = false,
        bgHeight = 7,
        qwerWidth = 26,
        qwerHeight = 3,
        qwerOffset = 0,
        timerOffset = 5,
    },
    -- Top:
    {       -- Teea 1
        onTop = true,
        bgHeight = 2,
        qwerWidth = 23,
        qwerHeight = 5,
        qwerOffset = 2,
        timerOffset = 4,
    },
    {       -- Teea 2
        onTop = true,
        bgHeight = 2,
        qwerWidth = 26,
        qwerHeight = 4,
        qwerOffset = 0,
        timerOffset = 3,
    },
    {       -- Farissi 3
        onTop = true,
        bgHeight = 7,
        qwerWidth = 26,
        qwerHeight = 3,
        qwerOffset = 0,
        timerOffset = 8,
    },
    {       -- Farissi 4
        onTop = true,
        bgHeight = 10,
        qwerWidth = 26,
        qwerHeight = 4,
        qwerOffset = 1,
        timerOffset = 8,
    },
    {       -- Teea 3
        onTop = true,
        bgHeight = 9,
        qwerWidth = 26,
        qwerHeight = 5,
        qwerOffset = 0,
        timerOffset = 7,
    },
}
local colorPresets = {
    {       -- Default Trackeee
        state0 = { 255, 191, 247, 84  }, -- green        (cool)     -- READY
        state4 = { 255, 120, 120, 120 }, -- gray         (cool)     -- COOLDOWN
        state5 = { 255, 86,  223, 255 }, -- light blue   (cool)     -- NOMANA
        state6 = { 255, 255, 157, 86  }, -- orange       (ok)       -- INRANGE
        state2 = { 255, 190, 190, 190 }, -- light gray   (ok)       -- SUPRESSED
        state3 = { 255, 190, 190, 190 }, -- light gray   (ok)       -- NOTLEARNED
        state7 = { 255, 247, 84,  84  }, -- red          (cool)     -- DANGER
    },
    {       -- Farissi 1
        state0 = { 255, 105, 255, 0   }, -- READY
        state4 = { 255, 135, 135, 135 }, -- COOLDOWN
        state5 = { 255, 0,   255, 255 }, -- NOMANA
        state6 = { 255, 255, 190, 0   }, -- INRANGE
        state2 = { 255, 94,  94,  94  }, -- SUPRESSED
        state3 = { 255, 241, 241, 241 }, -- NOTLEARNED
        state7 = { 255, 255, 0,   0   }, -- DANGER
    },
    {       -- Farissi 2
        state0 = { 255, 110, 201, 0   }, -- READY
        state4 = { 255, 87,  87,  87  }, -- COOLDOWN
        state5 = { 255, 0,   222, 224 }, -- NOMANA
        state6 = { 255, 224, 49,  0   }, -- INRANGE
        state2 = { 255, 77,  77,  77  }, -- SUPRESSED
        state3 = { 255, 121, 121, 121 }, -- NOTLEARNED
        state7 = { 255, 186, 0,   0   }, -- DANGER
    },
    {       -- Farissi 3
        state0 = { 255, 58,  163, 0   }, -- READY
        state4 = { 255, 66,  66,  66  }, -- COOLDOWN
        state5 = { 255, 0,   152, 189 }, -- NOMANA
        state6 = { 255, 189, 79,  0   }, -- INRANGE
        state2 = { 255, 55,  55,  55  }, -- SUPRESSED
        state3 = { 255, 105, 105, 105 }, -- NOTLEARNED
        state7 = { 255, 183, 0,   0   }, -- DANGER
    },
    {       -- Farissi 4
        state0 = { 255, 39,  133, 0   }, -- READY
        state4 = { 255, 44,  44,  44  }, -- COOLDOWN
        state5 = { 255, 0,   121, 165 }, -- NOMANA
        state6 = { 255, 165, 54,  0   }, -- INRANGE
        state2 = { 255, 37,  37,  37  }, -- SUPRESSED
        state3 = { 255, 75,  75,  75  }, -- NOTLEARNED
        state7 = { 255, 157, 0,   0   }, -- DANGER
    },
    {       -- Farissi 5
        state0 = { 255, 20,  178, 31  }, -- READY
        state4 = { 255, 56,  56,  56  }, -- COOLDOWN
        state5 = { 255, 10,  157, 189 }, -- NOMANA
        state6 = { 255, 246, 58,  0   }, -- INRANGE
        state2 = { 255, 67,  67,  67  }, -- SUPRESSED
        state3 = { 255, 122, 122, 122 }, -- NOTLEARNED
        state7 = { 255, 181, 0,   0   }, -- DANGER
    },
}
local globalUltAlerts = {
    Karthus = true,
    Shen = true,
    Soraka = true,
    Gangplank = true,
    Pantheon = true,
    TwistedFate = true,
    Nocturne = true,
}
local __T

-- Other functions:

function GetWebSpriteT(url, callback, folder)
    local urlr, sprite = url:reverse(), nil
    local filename, env = urlr:sub(1, urlr:find("/") - 1):reverse(), folder or GetCurrentEnv() and GetCurrentEnv().FILE_NAME and GetCurrentEnv().FILE_NAME:gsub(".lua", "") or "WebSprites"
    if FileExist(SPRITE_PATH .. env .. "\\" .. filename) then
        sprite = createSprite(env .. "\\" .. filename)
        if type(callback) == "function" then callback(sprite) end
    else
        if type(callback) == "function" then
            MakeSurePathExists(SPRITE_PATH .. env .. "\\" .. filename)
            DownloadFile(url, SPRITE_PATH .. env .. "\\" .. filename, function()
                if FileExist(SPRITE_PATH .. env .. "\\" .. filename) then
                    sprite = createSprite(env .. "\\" .. filename)
                end
                callback(sprite)
            end)
        else
            local finished = false
            sprite = GetWebSprite(url, function(data)
                finished = true
                sprite = data
            end)
            while not (finished or sprite or FileExist(SPRITE_PATH .. env .. "\\" .. filename)) do
                RunCmdCommand("ping 127.0.0.1 -n 1 -w 1")
            end
        end
        if not sprite and FileExist(SPRITE_PATH .. env .. "\\" .. filename) then
            sprite = createSprite(env .. "\\" .. filename)
        end
    end
    return sprite
end

local function logScale(sec,maxSec,multiplier)
    multiplier = multiplier or 9
    return math.log(1+sec/maxSec*multiplier,10)
end

local function Easing(step, sPos, tPos)
    step = step - 1
    return tPos * (step ^ 3 + 1) + sPos
end

local function DrawRectangleT(x,y,w,h,argb)
    DrawRectangle(floor(x),floor(y),floor(w),floor(h),argb)
end

local function DrawTextT(text,size,x,y,color,halign,valign)
--[[
    local textArea = GetTextArea(text or "", size or 12)
    x = (halign and ((halign:lower() == "right" and floor(x or 0) - textArea.x)
        or (halign:lower() == "center" and floor(x or 0) - textArea.x/2)))
        or floor(x or 0)
    y = (valign and ((valign:lower() == "bottom" and floor(y or 0) - textArea.y)
        or (valign:lower() == "center" and floor(y or 0) - textArea.y/2)))
        or floor(y or 0)
    DrawText(text or "", size or 12, x, y, color or 4294967295)
]]
    local textArea = GetTextArea(text or "", size or 12)
    valign, halign = valign and valign:lower() or "left", halign and halign:lower() or "top"
    x = (halign == "right" and x - textArea.x) or (halign == "center" and x - textArea.x/2) or x or 0
    y = (valign == "bottom" and y - textArea.y) or (valign == "center" and y - textArea.y/2) or y or 0
    DrawText(text or "", size or 12, floor(x), floor(y), color or 4294967295)
end

local function DrawLineT(x1,y1,x2,y2,size,argb)
    DrawLine(floor(x1),floor(y1),floor(x2),floor(y2),size,argb)
end

local function T_calcDmg(target, owner, mDmg, pDmg, tDmg)
    mDmg, pDmg, tDmg = mDmg or 0, pDmg or 0, tDmg or 0
    if mDmg > 0 then mDmg = owner:CalcMagicDamage(target, mDmg) end
    if pDmg > 0 then pDmg = owner:CalcDamage(target, pDmg) end
    return (mDmg + pDmg + tDmg)
end
local dotsTable = {
    --T_calcDmg(target, owner, mDmg, pDmg, tDmg)
    ["zephyrslamentburn"] =             function(t, o) return T_calcDmg(t, o, (t.health*.02), 0, 0) end,
    ["burning"] =                       function(t, o) return T_calcDmg(t, o, 0, 0, (8+2*o.level)/2) end,
    ["summonerdot"] =                   function(t, o) return T_calcDmg(t, o, 0, 0, (50+20*o.level)/5) end,
    ["brandablaze"] =                   function(t, o) return T_calcDmg(t, o, t.maxHealth*.02, 0, 0) end,
    ["caitlynyordletrap"] =             function(t, o) return T_calcDmg(t, o, (50*o:GetSpellData(_W).level+30+.6*o.ap)/1.5, 0, 0) end,
    ["cassiopeianoxiousblastpoison"] =  function(t, o) return T_calcDmg(t, o, (40*o:GetSpellData(_Q).level+35+.8*o.ap)/3, 0, 0) end,
    ["cassiopeiamiasmapoison"] =        function(t, o) return T_calcDmg(t, o, (10*o:GetSpellData(_W).level+15+.15*o.ap), 0, 0) end,
    ["dariushemo"] =                    function(t, o) return T_calcDmg(t, o, ((-.75)*((-1)^o.level-2*o.level-13)+.3*o.addDamage)/5, 0, 0) end,    -- per 1 stack and 1 second (max 5 stacks)
    ["fizzseastonetrident"] =           function(t, o) return T_calcDmg(t, o, ((10*o:GetSpellData(_W).level+20+.35*o.ap)+(o:GetSpellData(_W).level+3)*(t.maxHealth-t.health)/100)/3, 0, 0) end,
    ["scurvystrikeparticle"] =          function(t, o) return T_calcDmg(t, o, (3+o.level), 0, 0) end,  -- per 1 stack and 1 second (max 3 stacks)
    ["alzaharmaleficvisions"] =         function(t, o) return T_calcDmg(t, o, (60*o:GetSpellData(_E).level+20+.8*o.ap)/4, 0, 0) end,
    ["alzaharnethergrasp"] =            function(t, o) return T_calcDmg(t, o, (60*o:GetSpellData(_R).level+40+0.52*o.ap), 0, 0) end,
    ["mordekaiserchildrenofthegrave"] = function(t, o) return T_calcDmg(t, o, ((0.25*o:GetSpellData(_R).level+0.95+.002*o.ap)*t.maxHealth/100), 0, 0) end,
    ["bushwhackdamage"] =               function(t, o) return T_calcDmg(t, o, (45*o:GetSpellData(_W).level+35+.4*o.ap)/2, 0, 0) end,
    ["poisontrailtarget"] =             function(t, o) return T_calcDmg(t, o, (12*o:GetSpellData(_Q).level+10+.3*o.ap), 0, 0) end,
    ["swainbeamdamage"] =               function(t, o) return T_calcDmg(t, o, (15*o:GetSpellData(_Q).level+10+.3*o.ap), 0, 0) end,
    ["swaintorment"] =                  function(t, o) return T_calcDmg(t, o, ((40*o:GetSpellData(_E).level+35+.8*o.ap)*(3*o:GetSpellData(_E).level+5)/100)/4, 0, 0) end,
    ["talonbleeddebuff"] =              function(t, o) return T_calcDmg(t, o, 0, (10*o:GetSpellData(_Q).level+o.addDamage)/6, 0) end,
    ["toxicshotparticle"] =             function(t, o) return T_calcDmg(t, o, (6*o:GetSpellData(_E).level+.1*o.ap), 0, 0) end,
    ["bantamtracktarget"] =             function(t, o) return T_calcDmg(t, o, (31.25*o:GetSpellData(_R).level+18.75+.2*o.ap), 0, 0) end,
    ["explosiveshotdebuff"] =           function(t, o) return T_calcDmg(t, o, (40*o:GetSpellData(_E).level+70+o.ap)/5, 0, 0) end,
    ["deadlyvenom"] =                   function(t, o) return T_calcDmg(t, o, 0, 0, (2*floor(1+(o.level-1)/4.75))) end,       -- per 1 stack and 1 second (max 6 stacks and 6 seconds)
    ["udyrtigerpunchbleed"] =           function(t, o) return T_calcDmg(t, o, 0, (50*o:GetSpellData(_Q).level-20+(.1*o:GetSpellData(_Q).level+1.1)*o.damage)/2, 0) end,
    ["viktordeathraydot"] =             function(t, o) return T_calcDmg(t, o, (13.5*o:GetSpellData(_E).level+7.5+0.21*o.ap)/4, 0, 0) end,
}

local function GetJinxDamage(enemy, spell, dmgType) -- temporary solution. thanks 2 Kain!
	if not dmgType then dmgType = 1 end

	if spell == _W then
		return enemy:CalcDamage(myHero, ((35*(enemy:GetSpellData(_W).level-1) + 30) + (1.40 * enemy.addDamage)))
	elseif spell == _E then
		return enemy:CalcDamage(myHero, ((55*(enemy:GetSpellData(_E).level-1) + 120) + (1.00 * enemy.ap)))
	elseif spell == _R and dmgType == 1 then
		return enemy:CalcDamage(myHero, ((50*(enemy:GetSpellData(_R).level-1) + 150) + (.50 * enemy.addDamage) + (((5*enemy:GetSpellData(_R).level-1) + 25)/100 * (myHero.maxHealth - myHero.health))))
	elseif spell == _R and dmgType == 2 then
		return enemy:CalcDamage(myHero, ((100*(enemy:GetSpellData(_R).level-1) + 300) + (1.00 * enemy.addDamage) + (((5*enemy:GetSpellData(_R).level-1) + 25)/100 * (myHero.maxHealth - myHero.health))))
	elseif spell == _R and dmgType == 3 then
		return enemy:CalcDamage(myHero, ((100*(enemy:GetSpellData(_R).level-1) + 300) + (1.00 * enemy.addDamage)) + (((5*enemy:GetSpellData(_R).level-1) + 25)/100 * (myHero.maxHealth - myHero.health)) * .80)
	end
    return 0
end


-- Callbacks:

function OnLoad()
    print("Trackeee 2 Public Beta v0.93b")
    __T = Trackeee()
end

function OnTick()
    __T:Update()
end

function OnDraw()
    __T:OnDraw()
end

function OnProcessSpell(unit, spellProc)
    __T:OnProcessSpell(unit, spellProc)
end

function OnCreateObj(obj)
    __T:OnCreateObj(obj)
end

function OnUnload()
    ToggleShowName(true)
end

if VIP_USER then
    function OnRecvPacket(p)
        __T:OnRecvPacket(p)
    end
    function OnGainBuff(unit, buff)
        __T:OnBuff(unit, buff, false)
    end
    function OnUpdateBuff(unit, buff)
        __T:OnBuff(unit, buff, false)
    end
    function OnLoseBuff(unit, buff)
        __T:OnBuff(unit, buff, true)
    end
--[[    not used atm
    function OnGainVision(unit)
        if __T then __T:OnVision(unit, false) end
    end
    function OnLoseVision(unit)
        if __T then __T:OnVision(unit, true) end
    end
]]
end



-- Main:

class("Trackeee")
function Trackeee:__init()
    self.gameTime = GetInGameTimer()
    -- Getting HUD scale settings from the game config files:
    self.scale = self:GetHUDScale()
    self.updTick = 0
    -- Checking if script got loaded after first minions spawn to disable exp tracker:
    self.lateStart = (self.gameTime > 110) or false
    -- Sprites:
    self.sprites = {
        myriad = {
            loadTry = 1, sprite = nil, mirrors = {
                "https://dl.dropboxusercontent.com/u/93477088/BoL/Scripts/Trackeee 2/trackeee-myriad.png",
                "http://www.weee.ru/trackeee-myriad.png",
            }
        },
        expBar = {
            loadTry = 1, sprite = nil, mirrors = {
                "https://dl.dropboxusercontent.com/u/93477088/BoL/Scripts/Trackeee 2/trackeee-expbar.png",
                "http://www.weee.ru/trackeee-expbar.png",
            },
        },
        spells = {
            loadTry = 1, sprite = nil, mirrors = {
                "https://dl.dropboxusercontent.com/u/93477088/BoL/Scripts/Trackeee 2/trackeee-spells.png",
                "http://www.weee.ru/trackeee-spells.png",
            },
        },
        allyr = {
            loadTry = 1, sprite = nil, mirrors = {
                "https://dl.dropboxusercontent.com/u/93477088/BoL/Scripts/Trackeee 2/trackeee-allyr.png",
                "http://www.weee.ru/trackeee-allyr.png",
            },
        },
    }
    self.getWebSpriteTick = 0
    self.gotSprites = self:GetSprites()
    -- Config:
    self.config = self:Config()
    self.hideNames = self.config.detailed.show or self.config.namehealth.nameDisplayMode ~= 1
    -- Getting hero info and stuff:
    self.heroTable = self:BuildHeroTable()
    -- Color palette (colorblind, default, etc):
    local cPalette = self:GetColorPalette()
    self.colorMode = cPalette ~= 0 and 2 or 0
    UpdateWindow()

    return self
end

function Trackeee:GetSprites()
    local loadCount = 0
    local setTick = false
    for i, sprite in pairs(self.sprites) do
        if sprite.sprite ~= nil then
            loadCount = loadCount + 1
        elseif os.clock() >= self.getWebSpriteTick then
            setTick = true
            sprite.sprite = GetWebSpriteT(sprite.mirrors[sprite.loadTry], function(data) sprite.sprite = data end, "Trackeee 2")
            sprite.loadTry = sprite.loadTry >= #sprite.mirrors and 1 or sprite.loadTry + 1
        end
    end
    if setTick then self.getWebSpriteTick = os.clock() + 1 end
    return loadCount >= 4
end

function Trackeee:BuildHeroTable()
    local playerBarWidth, otherBarWidth, hM, heroTable = 140, 169, heroManager, {}
    for i = 1, hM.iCount do
        local hero = hM:GetHero(i)
        hero.iCount = i
        hero.barData = self:GetBarData(hero)
        hero.barWidth = (hero.isMe and playerBarWidth) or otherBarWidth
        hero.bar = Trackeee_DrawBar(self, hero)
        hero.shopaholic = hero.bar:Shopaholic()
        hero.updItemsTick, hero.cost, hero.exp, hero.expPct, hero.nameText = self.gameTime + 3, 0, 0, 0, "empty"
        hero.cds = {
            Trackeee_CD(hero, "CD", _Q, "Q", 4),
            Trackeee_CD(hero, "CD", _W, "W", 5),
            Trackeee_CD(hero, "CD", _E, "E", 6),
            Trackeee_CD(hero, "CD", _R, "R", 1),
            Trackeee_CD(hero, "SCD", SUMMONER_1, summonersTable[hero:GetSpellData(SUMMONER_1).name], 2),
            Trackeee_CD(hero, "SCD", SUMMONER_2, summonersTable[hero:GetSpellData(SUMMONER_2).name], 3),
        }
        hero.cds2 = {
            Trackeee_CD(hero, "BUFF", "exaltedwithbaronnashor", "Baron", 7),
            Trackeee_CD(hero, "BUFF", "blessingofthelizardelder", "Red", 8),
            Trackeee_CD(hero, "BUFF", "crestoftheancientgolem", "Blue", 9),
            Trackeee_CD(hero, "BUFF", "OracleElixirSight", "Oracle", 10, 400),
            Trackeee_CD(hero, "BUFF", "OracleExtractSight", "Oracle", 10, 250),
            Trackeee_CD(hero, "BUFF", "PotionOfGiantStrength", "Red Elixir", 11, 350),
            Trackeee_CD(hero, "BUFF", "PotionOfBrilliance", "Blue Elixir", 12, 250),
            Trackeee_CD(hero, "ICD", ITEM_1, "", 13),
            Trackeee_CD(hero, "ICD", ITEM_2, "", 14),
            Trackeee_CD(hero, "ICD", ITEM_3, "", 15),
            Trackeee_CD(hero, "ICD", ITEM_4, "", 16),
            Trackeee_CD(hero, "ICD", ITEM_5, "", 17),
            Trackeee_CD(hero, "ICD", ITEM_6, "", 18),
        }
        if passiveTable[hero.charName] then table.insert(hero.cds2, Trackeee_CD(hero, "PASSIVE", passiveTable[hero.charName], "Passive", 19)) end
        hero.dots = {}
        heroTable[#heroTable+1] = hero
    end
    return heroTable
end

function Trackeee:Config()
    local config = scriptConfig("Trackeee", "trackeee")


    -------------------------- QWER ---------------------------
    config:addSubMenu("Spell (QWER) Bars", "qwer")

        config.qwer:addSubMenu("Visual Settings", "visual")
            config.qwer.visual:addParam("onTop", "Draw UI on top of hpbar (no timeline)", SCRIPT_PARAM_ONOFF, false)
            config.qwer.visual:addParam("bgHeight", "BG Height", SCRIPT_PARAM_SLICE, 2, 2, 20, 0)
            config.qwer.visual:addParam("qwerWidth", "QWER Width", SCRIPT_PARAM_SLICE, 23, 15, 26, 0)
            config.qwer.visual:addParam("qwerHeight", "QWER Height", SCRIPT_PARAM_SLICE, 5, 3, 10, 0)
            config.qwer.visual:addParam("qwerOffset", "QWER Offset", SCRIPT_PARAM_SLICE, 0, 0, 13, 0)
            config.qwer.visual:addParam("timerOffset", "Timer Offset", SCRIPT_PARAM_SLICE, 5, 0, 13, 0)
            config.qwer.visual:addParam("innerShadow", "Inner shadow effect", SCRIPT_PARAM_ONOFF, true)

        config.qwer:addSubMenu("Spell States", "states")
            config.qwer.states:addParam("state"..READY,       "READY", SCRIPT_PARAM_COLOR,        { 255, 191, 247, 84  }) -- green        (cool)
            config.qwer.states:addParam("state"..COOLDOWN,    "COOLDOWN", SCRIPT_PARAM_COLOR,     { 255, 120, 120, 120 }) -- gray         (cool)
            config.qwer.states:addParam("state"..NOMANA,      "NOMANA", SCRIPT_PARAM_COLOR,       { 255, 86,  223, 255 }) -- light blue   (cool)
            config.qwer.states:addParam("state"..INRANGE,     "INRANGE", SCRIPT_PARAM_COLOR,      { 255, 255, 157, 86  }) -- orange       (ok)
            config.qwer.states:addParam("state"..NOTLEARNED,  "NOTLEARNED", SCRIPT_PARAM_COLOR,   { 255, 190, 190, 190 }) -- light gray   (ok)
            config.qwer.states:addParam("state"..SUPRESSED,   "SUPRESSED / PASSIVE", SCRIPT_PARAM_COLOR,    { 255, 190, 190, 190 }) -- light gray   (ok)
            config.qwer.states:addParam("state"..DANGER,      "DANGER", SCRIPT_PARAM_COLOR,       { 255, 247, 84,  84  }) -- red          (cool)

        config.qwer:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.qwer:addParam("desc","Display QWER bars for:", SCRIPT_PARAM_INFO, "")
        config.qwer:addParam("showSelf", "Player", SCRIPT_PARAM_ONOFF, true)
        config.qwer:addParam("showAlly", "Allies", SCRIPT_PARAM_ONOFF, true)
        config.qwer:addParam("showEnemy", "Enemies", SCRIPT_PARAM_ONOFF, true)
        config.qwer:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.qwer:addParam("showTimers", "Show timers for CDs less than: ", SCRIPT_PARAM_SLICE, 4, 0, 30, 0)
        config.qwer:addParam("showAllTimers", "Always show timers", SCRIPT_PARAM_ONOFF, false)
        config.qwer:addParam("showRTimers", "Always show R timers", SCRIPT_PARAM_ONOFF, true)
        config.qwer:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.qwer:addParam("enablePresets", "Preset mode (overwrites your settings):", SCRIPT_PARAM_ONOFF, false)
        config.qwer:addParam("lPreset", "Layout preset", SCRIPT_PARAM_SLICE, 1, 1, #layoutPresets, 0)
        config.qwer:addParam("cPreset", "Color preset", SCRIPT_PARAM_SLICE, 1, 1, #colorPresets, 0)


    ---------------------- Summoner Spells -----------------------
    config:addSubMenu("Summoner Spells", "summoners")
        config.summoners:addParam("desc","Display summoners for:", SCRIPT_PARAM_INFO, "")
        config.summoners:addParam("showSelf", "Player", SCRIPT_PARAM_ONOFF, true)
        config.summoners:addParam("showAlly", "Allies", SCRIPT_PARAM_ONOFF, true)
        config.summoners:addParam("showEnemy", "Enemies", SCRIPT_PARAM_ONOFF, true)
        config.summoners:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.summoners:addParam("showTimers", "Show timers for CDs less than: ", SCRIPT_PARAM_SLICE, 30, 0, 60, 0)
        config.summoners:addParam("showAllTimers", "Always show timers", SCRIPT_PARAM_ONOFF, false)


    ---------------------- OOM (Out Of Mana) ---------------------
    config:addSubMenu("OOM (Out Of Mana)", "oom")
        config.oom:addParam("desc","Central HUD (player only):", SCRIPT_PARAM_INFO, "")
        config.oom:addParam("cTimers", "Regen timers", SCRIPT_PARAM_ONOFF, true)
        config.oom:addParam("cTimersColor", "Regen timers text color:", SCRIPT_PARAM_COLOR, { 255, 255, 255, 255 })
        config.oom:addParam("cSpell", "Spell costs", SCRIPT_PARAM_ONOFF, false)
        config.oom:addParam("cCombo", "Combo cost", SCRIPT_PARAM_ONOFF, false)
        config.oom:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.oom:addParam("desc","Above champions (all players):", SCRIPT_PARAM_INFO, "")
        config.oom:addParam("showSelf", "Display for: Player", SCRIPT_PARAM_ONOFF, true)
        config.oom:addParam("showAlly", "Display for: Allies", SCRIPT_PARAM_ONOFF, true)
        config.oom:addParam("showEnemy", "Display for: Enemies", SCRIPT_PARAM_ONOFF, true)
        config.oom:addParam("aSpell", "Spell costs", SCRIPT_PARAM_ONOFF, false)
        config.oom:addParam("aCombo", "Combo cost", SCRIPT_PARAM_ONOFF, true)
        config.oom:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.oom:addParam("spellColor", "Spell cost marks color:", SCRIPT_PARAM_COLOR, { 255, 200, 200, 200 })
        config.oom:addParam("comboColor", "Combo cost marks color:", SCRIPT_PARAM_COLOR, { 255, 255, 50, 50 })


    ------------------------ Shopaholic --------------------------
    config:addSubMenu("Shopaholic", "shopaholic")
        config.shopaholic:addParam("desc","Display for:", SCRIPT_PARAM_INFO, "")
        config.shopaholic:addParam("showSelf", "Player", SCRIPT_PARAM_ONOFF, true)
        config.shopaholic:addParam("showAlly", "Allies", SCRIPT_PARAM_ONOFF, true)
        config.shopaholic:addParam("showEnemy", "Enemies", SCRIPT_PARAM_ONOFF, true)
        config.shopaholic:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.shopaholic:addParam("duration", "Display duration", SCRIPT_PARAM_SLICE, 2, 0.5, 3, 1)
        config.shopaholic:addParam("consumablesOnly", "Don't show sold / converted items", SCRIPT_PARAM_ONOFF, true)


    ----------------------- Detailed View ------------------------
    config:addSubMenu("Detailed View", "detailed")
    
        config.detailed:addSubMenu("Timeline [Semi-VIP]", "timeline")
            config.detailed.timeline:addParam("desc","Display timeline for:", SCRIPT_PARAM_INFO, "")
            config.detailed.timeline:addParam("showSelf", "Player", SCRIPT_PARAM_ONOFF, true)
            config.detailed.timeline:addParam("showAlly", "Allies", SCRIPT_PARAM_ONOFF, true)
            config.detailed.timeline:addParam("showEnemy", "Enemies", SCRIPT_PARAM_ONOFF, true)
            config.detailed.timeline:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
            config.detailed.timeline:addParam("desc","Appearance:", SCRIPT_PARAM_INFO, "")
            config.detailed.timeline:addParam("textSize", "Font size", SCRIPT_PARAM_SLICE, 12, 12, 30, 0)
            config.detailed.timeline:addParam("markerHeight", "Time marker height", SCRIPT_PARAM_SLICE, 4, 2, 12, 0)
            config.detailed.timeline:addParam("timerHeight", "Timer height", SCRIPT_PARAM_SLICE, 22, 18, 40, 0)
            config.detailed.timeline:addParam("color", "Color", SCRIPT_PARAM_COLOR, {255,255,255,255})
            config.detailed.timeline:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
            config.detailed.timeline:addParam("desc","Marks and math:", SCRIPT_PARAM_INFO, "")
            config.detailed.timeline:addParam("showMarksWhenCD", "Always show marks", SCRIPT_PARAM_ONOFF, true)
            config.detailed.timeline:addParam("lowSec", "Low CD", SCRIPT_PARAM_SLICE, 10, 0, 15, 0)
            config.detailed.timeline:addParam("midSec", "Mid CD", SCRIPT_PARAM_SLICE, 30, 20, 40, 0)
            config.detailed.timeline:addParam("maxSec", "Long CD", SCRIPT_PARAM_SLICE, 60, 50, 90, 0)
            config.detailed.timeline:addParam("longSec", "Longest CD", SCRIPT_PARAM_SLICE, 240, 120, 350, 0)
            config.detailed.timeline:addParam("longCdBarStartPos", "Long CD bar start position", SCRIPT_PARAM_SLICE, 90, 70, 95, 0)
    
        config.detailed:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.detailed:addParam("showKdaCostCs", "KDA / Cost / CS", SCRIPT_PARAM_ONOFF, true)
        config.detailed:addParam("spellLevelAlpha", "Spell level transparency", SCRIPT_PARAM_SLICE, 255, 0, 255, 0)
        config.detailed:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.detailed:addParam("show", "Enable detailed view", SCRIPT_PARAM_ONKEYDOWN, false, 16)
        config.detailed.show = false


    -------------------- Name & Health Text ----------------------
    config:addSubMenu("Name & Health Text", "namehealth")
        config.namehealth:addParam("desc","Name display:", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("nameDisplayMode", "Name display mode:", SCRIPT_PARAM_SLICE, 1, 0, 3, 0)
        config.namehealth:addParam("nameColor", "Name text color:", SCRIPT_PARAM_COLOR, { 220, 253, 253, 183 })    -- beige (like default in-game name)
        config.namehealth:addParam("nameSize", "Name text size:", SCRIPT_PARAM_SLICE, 17, 12, 20, 0)
        config.namehealth:addParam("nameOutline", "Outline quality:", SCRIPT_PARAM_SLICE, 0, 0, 2, 0)
        config.namehealth:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","0 - Off", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","1 - Player's name (default)", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","2 - Champion's name", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","3 - Health Text", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","HP display modes:", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("selfHpDisplay", "Player:", SCRIPT_PARAM_SLICE, 0, 0, 5, 0)
        config.namehealth:addParam("allyHpDisplay", "Allies:", SCRIPT_PARAM_SLICE, 0, 0, 5, 0)
        config.namehealth:addParam("enemyHpDisplay", "Enemies:", SCRIPT_PARAM_SLICE, 0, 0, 5, 0)
        config.namehealth:addParam("hpColor", "HP text color:", SCRIPT_PARAM_COLOR, { 220, 253, 253, 183 })    -- beige (like default in-game name)
        config.namehealth:addParam("hpOutline", "Outline quality:", SCRIPT_PARAM_SLICE, 0, 0, 2, 0)
        config.namehealth:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","0 - Off", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","1 - HP: Current/Max", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","2 - HP: Current", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","3 - HP: Percentage", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","4 - HP: Effective Physical", SCRIPT_PARAM_INFO, "")
        config.namehealth:addParam("desc","5 - HP: Effective Magical", SCRIPT_PARAM_INFO, "")


    ------------------------ Performance -------------------------
    config:addSubMenu("Performance", "performance")
        config.performance:addParam("updateRate", "Trackeee update rate", SCRIPT_PARAM_SLICE, 0.1, 0.1, 1, 1)
        config.performance:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.performance:addParam("desc","Settings:", SCRIPT_PARAM_INFO, "")
        config.performance:addParam("setLowSettings", "Set Low settings", SCRIPT_PARAM_ONOFF, false)
        config.performance:addParam("setLidSettings", "Set Mid settings", SCRIPT_PARAM_ONOFF, false)
        config.performance:addParam("setHighSettings", "Set High settings", SCRIPT_PARAM_ONOFF, false)
        config.performance:addParam("setUltraSettings", "Set Ultra settings", SCRIPT_PARAM_ONOFF, false)
        config.performance.setLowSettings = false
        config.performance.setMidSettings = false
        config.performance.setHighSettings = false
        config.performance.setUltraSettings = false


    -------------------------- Waiteee ---------------------------
    config:addSubMenu("Waiteee [VIP]", "waiteee")
        config.waiteee:addParam("desc","Display waiteee for:", SCRIPT_PARAM_INFO, "")
        config.waiteee:addParam("showSelf", "Player", SCRIPT_PARAM_ONOFF, true)
        config.waiteee:addParam("showAlly", "Allies", SCRIPT_PARAM_ONOFF, true)
        config.waiteee:addParam("showEnemy", "Enemies", SCRIPT_PARAM_ONOFF, true)
        config.waiteee:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.waiteee:addParam("displayMode", "Countdown display mode:", SCRIPT_PARAM_SLICE, 1, 1, 2, 0)
        config.waiteee:addParam("desc","1 - 3", SCRIPT_PARAM_INFO, "")
        config.waiteee:addParam("desc","2 - 0:03", SCRIPT_PARAM_INFO, "")
        config.waiteee:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
        config.waiteee:addParam("desc","To make it work you have to", SCRIPT_PARAM_INFO, "")
        config.waiteee:addParam("desc","enable 'Status' Floating Text", SCRIPT_PARAM_INFO, "")
        config.waiteee:addParam("desc","in Options -> Interface.", SCRIPT_PARAM_INFO, "")


    -------------------------- GENERAL ---------------------------
    config:addParam("showAllyExp", "Ally experience bars [VIP]", SCRIPT_PARAM_ONOFF, true)
    config:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
    config:addParam("configMode", "Enable testing mode", SCRIPT_PARAM_ONOFF, false)
    config:addParam("refreshGUI", "Refresh GUI (After scale change)", SCRIPT_PARAM_ONOFF, false)
    config:addParam("blankspace","", SCRIPT_PARAM_INFO, "")
    config:addParam("stopDrawing", "Hide Trackeee while holding...", SCRIPT_PARAM_ONKEYDOWN, false, 9)
    config.configMode = false
    config.refreshGUI = false
    config.stopDrawing = false

    return config
end

function Trackeee:Update()
    self.gameTime = GetInGameTimer()
    if not self.gotSprites then self.gotSprites = self:GetSprites() end
    if self.updTick <= self.gameTime and self.gotSprites then
        if self.config.refreshGUI then self.scale = self:GetHUDScale() self.config.refreshGUI = false end
        self:SetSettings()
        self:ChangePresets()
        for i, hero in pairs(self.heroTable) do
            self:UpdateCDs(hero)
            self:CalcHeroCost(hero)
            hero.expPct = self:CalcHeroExpPct(hero)   -- VIP only
            hero.shopaholic:Update()
            self:Waiteee(hero)
        end
        self.updTick = self.gameTime + self.config.performance.updateRate
    end
    self:ToggleShowName()
end

function Trackeee:UpdateCDs(hero)
    for i, cd in pairs(hero.cds) do
        cd:ConfigMode(self.config.configMode)
        cd:Update(self.gameTime, self.config.detailed.timeline.textSize)
    end
    -- Sorting CDs, buffs and other stuff by time left and then by priority (in case if time is the same)
    if self.config.detailed.show and not self.config.qwer.visual.onTop and hero.bar.valid then
        for i, cd in pairs(hero.cds2) do
            cd:ConfigMode(self.config.configMode)
            cd:Update(self.gameTime, self.config.detailed.timeline.textSize)
        end
        table.sort(hero.cds2, function(a,b) return (a.cd == b.cd and a.prio > b.prio) or a.cd > b.cd end)
    end
end

function Trackeee:CalcHeroCost(hero)
    if self.config.detailed.showKdaCostCs and self.config.detailed.show and hero.bar.valid then
        if hero.updItemsTick < self.gameTime then
            hero.cost = 0
            for slot = ITEM_1, ITEM_6 do
                local rItem = hero:getItem(slot)
                local item = ( rItem and rItem.id >= 1000 and GetItem(rItem.id) ) or nil
                if item and item.gold.total and item.gold.total > 0 then
                    hero.cost = hero.cost + item.gold.total * rItem.stacks
                end
            end
            for j, cd in pairs(hero.cds2) do
                if cd.type == "BUFF" and TargetHaveBuff(cd.key, hero) and cd.buffCost and cd.cd > 0 then
                    hero.cost = hero.cost + cd.buffCost
                end
            end
            hero.updItemsTick = self.gameTime + 3    -- once in 3 seconds
        end
    else
        hero.updItemsTick = 0
    end
end

function Trackeee:CalcHeroExpPct(hero)
    return not self.lateStart and VIP_USER and hero.bar.valid and ( hero.level == 18 and 0 or max(0, math.round(((hero.exp - lvlTable[hero.level-1]) / (lvlTable[hero.level] - lvlTable[hero.level-1]))*100)) ) or 0
end

function Trackeee:ToggleShowName()
    if self.hideNames == (self.config.detailed.show or self.config.namehealth.nameDisplayMode ~= 1) then
        self.hideNames = not (self.config.detailed.show or self.config.namehealth.nameDisplayMode ~= 1)
        ToggleShowName(self.hideNames)
    end
end

function Trackeee:Waiteee(hero)
    if (not self.config.waiteee.showSelf and hero.isMe)
    or (not self.config.waiteee.showAlly and hero.team == player.team and not hero.isMe)
    or (not self.config.waiteee.showEnemy and hero.team == TEAM_ENEMY) then
        return
    end
    local gameTime = GetGameTimer()
    if hero.immuneEndT and hero.immuneEndT >= gameTime then
        local secondsLeft = math.ceil(math.max(0, hero.immuneEndT - gameTime))
        if hero.immuneText and hero.immuneText ~= "" .. ((self.config.waiteee.displayMode == 2 and TimerText(secondsLeft)) or secondsLeft) then
            hero.immuneText = "" .. ((self.config.waiteee.displayMode == 2 and TimerText(secondsLeft)) or secondsLeft)
            PrintFloatText(hero, 10, hero.immuneText)
        end
    end
end

function Trackeee:OnDraw()
    if self.gotSprites then
        for i, hero in pairs(self.heroTable) do
            hero.bar:DrawUpdate()
            if not self.config.stopDrawing and hero.bar.valid then
                hero.bar:BgOverlay()
                hero.shopaholic:OnDraw()
                hero.bar:HealthText()
                hero.bar:QWER()
                hero.bar:Summoners()
                hero.bar:Mana()
                hero.bar:IsDying()  -- VIP only
                hero.bar:TimeLine() -- Only for bottom layouts
                hero.bar:Extra()
                hero.bar:Name()
                hero.bar:Exp()          -- VIP only
            end
            --hero.bar:AllyR()
            self:CentralManaHUD(hero)
        end
    end
end

function Trackeee:CentralManaHUD(hero)
    if not hero.isMe or (not self.config.oom.cTimers and not self.config.oom.cSpell and not self.config.oom.cCombo) then return end
    if hero.parType == 0 or hero.parType == 1 then
        local scale = self.scale
        local centerX = WINDOW_W/2 + 10*(1+scale)           -- center of the HUD
        local centralHUD = 315 * (1+scale)                  -- HUD size
        local actionBarX = centerX - centralHUD*0.27        -- action bar X pos
        local actionBarY = WINDOW_H - 60*(1+scale)          -- action bar Y pos
        local iconSize = 20*(1+scale)                       -- spell icon size
        local iconGap = 30.5*(1+scale)                      -- spell icon offset
        local manaBar = { w = 235 * (1+scale), h = 10 * (1+scale), y = WINDOW_H - 14*(1+scale) }
        manaBar.x = centerX - manaBar.w/2
        local comboCost = 0
        for i, cd in pairs(hero.cds) do
            if cd.type == "CD" then
                local cdOrder = (cd.name == "Q" and 1) or (cd.name == "W" and 2) or (cd.name == "E" and 3) or (cd.name == "R" and 4) or 0
                if cdOrder > 0 then
                    local manaCost = hero:GetSpellData(cd.key).mana
                    if cd.cd == 0 then comboCost = comboCost + manaCost end
                    if cd.OOM > 0 then
                        local c = self.config.oom.spellColor
                        cd.alphaOOM = (cd.OOM >= 0.5 and cd.alphaOOM < c[1] and min(c[1], cd.alphaOOM + 4)) or (cd.OOM < 0.5 and cd.alphaOOM > 0 and max(0,cd.alphaOOM-2)) or cd.alphaOOM
                        local newColor = ARGB(cd.alphaOOM, c[2], c[3], c[4])
                        if self.config.oom.cSpell then
                            DrawLineT(manaBar.x + manaBar.w*(manaCost/hero.maxMana), manaBar.y, manaBar.x + manaBar.w*(manaCost/hero.maxMana), manaBar.y + manaBar.h, 1, newColor)
                        end
                        if self.config.oom.cTimers then
                            local c = self.config.oom.cTimersColor
                            if hero:CanUseSpell(cd.key) == NOMANA then
                                local textSize = ceil(15*(1+scale))
                                DrawTextA(cd.OOMText,textSize,actionBarX + iconGap*(cdOrder-1) + iconSize/2, actionBarY+iconSize*.15, ARGB(cd.alphaOOM,c[2],c[3],c[4]),"center")
                            elseif hero:CanUseSpell(cd.key) == COOLDOWN then
                                local textSize = ceil(8*(1+scale))
                                DrawTextA("+"..cd.OOMText,textSize,actionBarX + iconGap*(cdOrder-1) + iconSize/2, actionBarY-iconSize*.6, ARGB(cd.alphaOOM,c[2],c[3],c[4]),"center")
                            end
                        end
                    else
                        cd.alphaOOM = 0
                    end
                end
            end
        end
        -- Drawing combo mana-cost mark on central HUD:
        if hero.mana < comboCost and self.config.oom.cCombo then
            local c = self.config.oom.comboColor
            DrawLineT(manaBar.x + manaBar.w*(comboCost/hero.maxMana),
                      manaBar.y,
                      manaBar.x + manaBar.w*(comboCost/hero.maxMana),
                      manaBar.y + manaBar.h,
                      1, ARGB(c[1],c[2],c[3],c[4])
            )
        end
    end
end

function Trackeee:OnProcessSpell(unit, spellProc)
    if unit and unit.valid and unit.type == "obj_AI_Hero" then
        for i, hero in pairs(self.heroTable) do
            if unit.networkID == hero.networkID then
                for k, cd in pairs(hero.cds) do
                    if cd.type == "SCD" and summonersTable[spellProc.name] == cd.name then
                        cd.capturedCast = true
                        return
                    end
                    if hero.team == TEAM_ENEMY and cd.type == "CD" and cd.name == "R" and globalUltAlerts[hero.charName] and hero:GetSpellData(cd.key).name == spellProc.name then
                        PrintAlert(hero.charName .. " used ultimate!", 3, 50, 255, 50)
                    end
                end
            end
        end
    end
end

function Trackeee:OnRecvPacket(p)
    if not self.lateStart and p.header == 0x10 then    -- exp gain
        p.pos = 5
        local networkID = p:DecodeF()
        local amount = floor(p:DecodeF())
        for i, hero in pairs(self.heroTable) do
            if hero.networkID == networkID then hero.exp = hero.exp + amount end
        end
    end
end

function Trackeee:OnCreateObj(obj)
    for i, hero in pairs(self.heroTable) do
        for j, immune in pairs(immuneTable) do
            if obj and obj.valid and GetDistance(obj,hero) <= 80 and obj.name == immune[1] then
                hero.immuneEndT = self.gameTime + immune[2]
                hero.immuneText = ""
                return
            end
        end
    end
end

function Trackeee:OnBuff(unit, buff, isLose)
    if unit and unit.valid then
        for i, hero in pairs(self.heroTable) do
            if hero and hero.networkID == unit.networkID then
                -- Checking for BUFF or PASSIVE:
                for j, cd in pairs(hero.cds2) do
                    if (cd.type == "BUFF" or cd.type == "PASSIVE") and cd.key == buff.name then
                        cd.endT = isLose and 0 or buff.endT
                        return
                    end
                end
                if buff.source and buff.source.valid then
                    -- Checking for dots:
                    local dmg = dotsTable[buff.name:lower()] and type(dotsTable[buff.name:lower()]) == "function" and dotsTable[buff.name:lower()](unit, buff.source) or 0
                    if dmg > 0 then
                        if isLose then
                            hero.dots[buff.name] = nil
                        else
                            hero.dots[buff.name] = hero.dots[buff.name] or {}
                            hero.dots[buff.name].endT = buff.endT
                            hero.dots[buff.name].source = buff.source
                            hero.dots[buff.name].duration = buff.duration
                            hero.dots[buff.name].dmg = dmg*(buff.stack and buff.stack > 0 and buff.stack or 1)
                        end
                    end
                end
            end
        end
    end
end

--[[ function Trackeee:OnVision(unit, isLose)    -- not used atm
    if unit and unit.valid then
        for i, hero in pairs(self.heroTable) do
            if hero and hero.networkID == unit.networkID then
                hero.rendered = isLose == true and false or true
            end
        end
    end
end
]]

function Trackeee:GetBarData(hero)
    return hero and (hero.isMe and GetSelfBarData() or hero.team == TEAM_ENEMY and GetEnemyBarData() or GetFriendlyBarData()) or nil
end

function Trackeee:ChangePresets()
    if self.config.qwer.enablePresets then
        for i, param in pairs(layoutPresets[self.config.qwer.lPreset]) do
            self.config.qwer.visual[i] = param
        end
        for i, state in pairs(colorPresets[self.config.qwer.cPreset]) do
            for j, el in pairs(state) do
                self.config.qwer.states[i][j] = el
            end
        end
    end
end

function Trackeee:SetSettings()
    if self.config.performance.setLowSettings then
        self.config.performance.setLowSettings = false
        self.config.performance.updateRate = 1
        self.config.namehealth.nameOutline = 0
        self.config.namehealth.hpOutline = 0
        self.config.qwer.visual.innerShadow = false
    elseif self.config.performance.setMidSettings then
        self.config.performance.setMidSettings = false
        self.config.performance.updateRate = 0.5
        self.config.namehealth.nameOutline = 0
        self.config.namehealth.hpOutline = 0
        self.config.qwer.visual.innerShadow = false
    elseif self.config.performance.setHighSettings then
        self.config.performance.setHighSettings = false
        self.config.performance.updateRate = 0.1
        self.config.namehealth.nameOutline = 1
        self.config.namehealth.hpOutline = 1
        self.config.qwer.visual.innerShadow = true
    elseif self.config.performance.setUltraSettings then
        self.config.performance.setUltraSettings = false
        self.config.performance.updateRate = 0.1
        self.config.namehealth.nameOutline = 2
        self.config.namehealth.hpOutline = 2
        self.config.qwer.visual.innerShadow = true
    end
end

function Trackeee:GetColorPalette()
    local gameSettings = GetGameSettings()
    return gameSettings and gameSettings.ColorPalette and gameSettings.ColorPalette.ColorPalette or nil
end

function Trackeee:GetHUDScale()
    -- "cropped" from gReY's allclass GetMinimap stuff
    local gameSettings = GetGameSettings()
    local windowWidth, windowHeight = WINDOW_W, WINDOW_H
    if gameSettings and gameSettings.General and gameSettings.General.Width and gameSettings.General.Height then
        windowWidth, windowHeight = gameSettings.General.Width, gameSettings.General.Height
        local path = GAME_PATH.."DATA\\menu\\hud\\hud"..windowWidth.."x"..windowHeight..".ini"
        local hudSettings = ReadIni(path)
        if hudSettings and hudSettings.Globals and hudSettings.Globals.GlobalScale then
            return hudSettings.Globals.GlobalScale
        else
            print("GetHUDScale(): something is wrong with ReadIni(path)")
            return nil
        end
    else
        print("GetHUDScale(): something is wrong with GetGameSettings()")
        return nil
    end
end



class("Trackeee_CD")
function Trackeee_CD:__init(hero, type, key, name, prio, buffCost)
    self.hero, self.type, self.key, self.name, self.prio, self.buffCost = hero, type, key, name, prio, buffCost
    self.cd, self.cdPos, self.alpha, self.yOffset, self.yOffsetTar = 0, 0, 0, 0, 0
    if type == "CD" then
        self.OOM, self.alphaOOM, self.OOMText = 0, 0, ""
        self.state, self.inRange, self.danger, self.supressed, self.notLearned = 0, false, false, false, false
    elseif type == "BUFF" or type == "ICD" or type == "PASSIVE" then
        self.timerText = ""
        self.textArea = { x = 0, y = 0 }
    end
    return self
end

function Trackeee_CD:Update(gameTime, textSize)
    if self.type == "CD" then
        self.cd = self.testCd or (self.hero:GetSpellData(self.key).currentCd > 0 and self.hero:GetSpellData(self.key).currentCd) or 0
        self.OOM = (self.hero.mpRegen > 0 and (self.hero:CanUseSpell(self.key) == NOMANA or self.hero:CanUseSpell(self.key) == COOLDOWN) and (self.hero:GetSpellData(self.key).mana - self.hero.mana) / self.hero.mpRegen) or 0
        self.OOMText = "" .. ((self.OOM >= 60 and TimerText(self.OOM)) or ceil(self.OOM))
        -- States:
        local dmg = self.hero.charName == "Jinx" and GetJinxDamage(self.hero, self.key, 3) or getDmg(self.name, myHero, self.hero, 3) or 0
        --local dmg = getDmg(self.name, myHero, self.hero, 3) or 0
        self.danger = self.hero.team == TEAM_ENEMY and dmg >= myHero.health
        self.inRange = self.hero.team == TEAM_ENEMY
                       and (inRangeExceptions[self.hero.charName] and not inRangeExceptions[self.hero.charName][self.key] or not inRangeExceptions[self.hero.charName])
                       and GetDistance(myHero,self.hero) <= self.hero:GetSpellData(self.key).range and self.hero:GetSpellData(self.key).range < 10000
        self.notLearned = self.hero:CanUseSpell(self.key) == NOTLEARNED
        self.supressed = self.hero.isMe and self.hero:CanUseSpell(self.key) == SUPRESSED
        self.state =  self.testState or
                    self.notLearned and NOTLEARNED or
                    self.cd > 0 and COOLDOWN or
                    self.OOM > 0 and NOMANA or
                    self.supressed and SUPRESSED or
                    self.danger and DANGER or
                    self.inRange and INRANGE or
                    READY
        if self.name == "R" and self.hero.team == TEAM_ENEMY and globalUltAlerts[self.hero.charName] then
            if not self.waitForCdAlert and self.cd > 0 then
                self.waitForCdAlert = true
            elseif self.waitForCdAlert and self.cd <= 0 then
                self.waitForCdAlert = nil
                PrintAlert(self.hero.charName .. " ultimate ready!", 3, 255, 50, 50)
            end
        end
    elseif self.type == "SCD" then
        self.cd = self.testCd or (self.hero:GetSpellData(self.key).currentCd > 0 and self.hero:GetSpellData(self.key).currentCd) or 0
        self.realMaxCd = self.realMaxCd or self.capturedCast and ceil(self.hero:GetSpellData(self.key).currentCd) or nil
    elseif (self.type == "BUFF" or self.type == "PASSIVE") and VIP_USER then
        self.cd = self.testCd or (self.endT and self.endT > 0 and max(0, self.endT - GetGameTimer())) or 0
        self.timerText = " " .. self.name .. ": " .. ((self.cd >= 60 and TimerText(self.cd)) or ceil(self.cd))
        self.textArea = GetTextArea(self.timerText or "", textSize)
    elseif self.type == "ICD" then
        if self.hero:getItem(self.key) then
            local item = self.hero:getItem(self.key)
            self.name = itemTable[item.id] or item.name or "Item"
            self.cd = self.hero:GetSpellData(self.key).currentCd > 0 and self.hero:GetSpellData(self.key).currentCd or 0
        else
            self.cd = 0
        end
        self.timerText = " " .. self.name .. ": " .. ((self.cd >= 60 and TimerText(self.cd)) or ceil(self.cd))
        self.textArea = GetTextArea(self.timerText or "", textSize)
    end
end

function Trackeee_CD:ConfigMode(configMode)
    if configMode and self.testCd == nil then
        if self.type == "CD" then
            local statesOrderRNG = self.hero.team == player.team and {0,2,4,5} or {0,2,3,4,5,6,7}
            self.testLv = math.random(0, self.name == "R" and 3 or 5)
            self.testState = self.testLv == 0 and 2 or statesOrderRNG[math.random(1,#statesOrderRNG)]
            self.testCd = (self.testState == COOLDOWN or self.testState == NOMANA) and (self.name == "R" and math.random(40,140) or math.random(0,23)) or 0
        elseif self.type == "SCD" then
            self.testCd = floor(math.random(0,3)) >= 1 and math.random(0,240) or 0
        elseif self.type == "BUFF" then
            self.testCd = floor(math.random(0,14)) <= 1 and math.random(4,170) or 0
        end
    elseif not configMode then
        self.testLv, self.testCd, self.testState = nil, nil, nil
    end
end



class("Trackeee_DrawBar")
function Trackeee_DrawBar:__init(t, hero)
    self.t = t
    self.hero = hero
    self.barWidth = hero.barWidth
    self.barHeight = 47
    self.barOffsetX, self.barOffsetY = 27, 37
    self.manaBarWidth = 104
    self.bgWidth = 110
    self.bgColors = {
        [0] = {     -- normal/default
            player = { top = ARGB(255,173,182,177), bot = ARGB(255,124,156,168) },
            ally = { top = ARGB(255,96,150,92), bot = ARGB(255,80,136,78) },
            enemy = { top = ARGB(255,189,103,86), bot = ARGB(255,181,92,73) },
        },
        [2] = {     -- colorblind
            player = { top = ARGB(255,173,182,177), bot = ARGB(255,124,156,168) },
            ally = { top = ARGB(255,77,135,151), bot = ARGB(255,62,122,138) },
            enemy = { top = ARGB(255,189,103,86), bot = ARGB(255,181,92,73) },
        },
    }
    self.cdSlotPos = { x = 0, y = 0 }
    self:DrawUpdate()
end

function Trackeee_DrawBar:DrawUpdate()
    self.barPos = GetUnitHPBarPos(self.hero)
    local barPosOffset = GetUnitHPBarOffset(self.hero)
    local barPosPercentageOffset = { x = self.hero.barData.PercentageOffset.x, y = self.hero.barData.PercentageOffset.y }
    self.barPos.x = self.barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * self.barWidth + self.barOffsetX
    self.barPos.y = self.barPos.y + (barPosOffset.y - 0.5 + barPosPercentageOffset.y) * self.barHeight + self.barOffsetY
    self.qwerWidth = math.limit(self.t.config.qwer.visual.qwerWidth, 15, 26)
    self.qwerHeight = math.limit(self.t.config.qwer.visual.qwerHeight, 3, 10)
    self.qwerOffset = math.limit(self.t.config.qwer.visual.qwerOffset, 0, 13)   -- y
    self.timerOffset = math.limit(self.t.config.qwer.visual.timerOffset, 0, 13) -- y
    self.spellLevelAlpha = self.t.config.detailed.spellLevelAlpha
    self.bgHeight = math.limit(self.t.config.qwer.visual.bgHeight, 2, 20)
    self.onTop = self.t.config.qwer.visual.onTop
    self.valid = self.hero and not self.hero.dead and self.hero.visible and OnScreen(self.barPos.x, self.barPos.y)
    if self.onTop then
        if self.hero.barData.TitleTextOffset.y ~= 6-self.bgHeight-self.qwerOffset-self.qwerHeight then self.hero.barData.TitleTextOffset.y = 6-self.bgHeight-self.qwerOffset-self.qwerHeight end
        if self.hero.barData.LoCOffset.y ~= 38 then self.hero.barData.LoCOffset.y = 38 end
    else
        if self.hero.barData.TitleTextOffset.y ~= 4 then self.hero.barData.TitleTextOffset.y = 4 end
        if self.hero.barData.LoCOffset.y ~= 38 + self.qwerOffset + self.qwerHeight + self.timerOffset + 7 then self.hero.barData.LoCOffset.y = 38 + self.qwerOffset + self.qwerHeight + self.timerOffset + 7 end
    end
end

function Trackeee_DrawBar:BgOverlay()
    if not self.t.config.detailed.show and ((not self.t.config.qwer.showSelf and self.hero.isMe)
    or (not self.t.config.qwer.showAlly and self.hero.team == player.team and not self.hero.isMe)
    or (not self.t.config.qwer.showEnemy and self.hero.team == TEAM_ENEMY)) then
        return
    end
    local h = self.bgHeight
    if h > 2 then
        local x, y, w = self.barPos.x, self.barPos.y, self.bgWidth
        local color, topOffset = 0, 0
        if self.onTop then
            color = (self.hero.isMe and self.bgColors[self.t.colorMode].player.top) or (self.hero.team == player.team and self.bgColors[self.t.colorMode].ally.top) or (self.hero.team == TEAM_ENEMY and self.bgColors[self.t.colorMode].enemy.top)
            topOffset, y = 2, y - 20 - h - 2
        else
            color = (self.hero.isMe and self.bgColors[self.t.colorMode].player.bot) or (self.hero.team == player.team and self.bgColors[self.t.colorMode].ally.bot) or (self.hero.team == TEAM_ENEMY and self.bgColors[self.t.colorMode].enemy.bot)
            topOffset, y = 0, y - 3
        end
        DrawRectangleT(x, y, w+3, h+2, ARGB(255,0,0,0))                                         -- outline
        DrawRectangleT(x + 1 + (not self.hero.isMe and 1 or 0), y + topOffset, w, h, color)     -- fill
    end
end

function Trackeee_DrawBar:QWER()
    if not self.t.config.detailed.show and ((not self.t.config.qwer.showSelf and self.hero.isMe)
    or (not self.t.config.qwer.showAlly and self.hero.team == player.team and not self.hero.isMe)
    or (not self.t.config.qwer.showEnemy and self.hero.team == TEAM_ENEMY)) then
        return
    end
    local width, height, yOffset, timerYOffset = self.qwerWidth, self.qwerHeight, self.qwerOffset, self.timerOffset
    local spellOrder = { _Q, _W, _E, _R }
    local stateColors = self.t.config.qwer.states
    local uiBarWidth = self.manaBarWidth
    local uiBarOffset = 4 + (not self.hero.isMe and 1 or 0)     -- x
    local gap = (uiBarWidth - width*4)/3 + width
    local x = self.barPos.x + uiBarOffset
    local y = self.onTop and (self.barPos.y - 21 - height - yOffset) or (self.barPos.y + yOffset)
    -- Iterating from Q to R:
    for i = 0, 3 do
        for j, cCd in pairs(self.hero.cds) do
            if cCd.type == "CD" and cCd.key == spellOrder[i+1] then
                -- Drawing outline:
                DrawRectangleT(x+gap*i-1, y-2, width+2, height+2, ARGB(255,0,0,0))
                -- Getting state color:
                local c = stateColors["state"..cCd.state]
                if c and type(c) == "table" then
                    if not self.t.config.qwer.visual.innerShadow then
                        DrawRectangleT(x + gap*i, y - 1, width, height, ARGB(c[1],c[2],c[3],c[4]))
                    else
                        local c2 = { c[1], max(0,c[2]-70), max(0,c[3]-70), max(0,c[4]-70) }
                        -- Drawing filler (state):
                        DrawRectangleT(x + gap*i, y - 1, width, height, ARGB(c2[1],c2[2],c2[3],c2[4]))
                        DrawRectangleT(x + 1 + gap*i, y, width - 2, height - 2, ARGB(c[1],c[2],c[3],c[4]))
                    end
                end

                --local cd = (cCd.cd > 0 and (cCd.cd <= 30 or self.t.config.general.showExtra) and cCd.cd) or (cCd.cd == 0 and cCd.OOM > 0 and cCd.OOM) or 0
                local cd = (cCd.cd > 0 and cCd.cd) or (cCd.cd == 0 and cCd.OOM > 0 and cCd.OOM) or 0

                -- Progress point bar (< 5 sec)
                if cd > 0 and cd < 5 then
                    local progress = width * (4-floor(cd))/5
                    if progress > 0 then
                        c = (cCd.state == COOLDOWN and cCd.OOM > 0 and stateColors["state"..NOMANA])
                            or ((cCd.state == COOLDOWN or cCd.state == NOMANA) and
                                  (
                                    (cCd.notLearned and stateColors["state"..NOTLEARNED])
                                     or (cCd.danger and stateColors["state"..DANGER])
                                     or (cCd.supressed and stateColors["state"..SUPRESSED])
                                     or (cCd.inRange and stateColors["state"..INRANGE])
                                     or stateColors["state"..READY]
                                  )
                               )
                        if c and type(c) == "table" then
                            if not self.t.config.qwer.visual.innerShadow then
                                DrawRectangleT(x + gap*i, y - 1, progress, height, ARGB(c[1],c[2],c[3],c[4]))
                            else
                                local c2 = { c[1], max(0,c[2]-70), max(0,c[3]-70), max(0,c[4]-70) }
                                DrawRectangleT(x + gap*i, y - 1, progress, height, ARGB(c2[1],c2[2],c2[3],c2[4]))
                                DrawRectangleT(x + 1 + gap*i, y, progress - 2, height - 2, ARGB(c[1],c[2],c[3],c[4]))
                            end
                        end
                    end
                end
                if cd > 0 and (cd <= self.t.config.qwer.showTimers or self.t.config.detailed.show or self.t.config.qwer.showAllTimers or (cCd.name == "R" and self.t.config.qwer.showRTimers)) then
                    local tY = self.onTop and y - height - 6 - timerYOffset or y + timerYOffset
                    self:Timer(x + gap*i + width/2 - 2, tY, cd)
                    if self.onTop and self.hero.barData.TitleTextOffset.y ~= 4-height*2-yOffset-timerYOffset-3 then self.hero.barData.TitleTextOffset.y = 4-height*2-yOffset-timerYOffset-3 end
                end
                if self.t.config.detailed.show then
                    self:SpellLevel(x + gap*i + width/2, self.onTop and y+height-2 or y-15, cCd)
                end
                break
            end
        end
    end
end

function Trackeee_DrawBar:AllyR()
    local hero = self.hero
    if hero.team == player.team and not hero.isMe then
        local cd = hero:GetSpellData(_R).currentCd
        local scale = self.t.scale
        local x, y = 18.5*(1+scale), 77.7*(1+scale)+41.4*(1+scale)*(hero.iCount-2)
        self.t.sprites.allyr.sprite:SetScale((1+scale)/2.15,(1+scale)/2.15)
        self.t.sprites.allyr.sprite:Draw(x, y, 255)
        if cd > 0 and self.t.config.detailed.show then
            self:Timer(x + 30, y, cd)
        end
    end
end

function Trackeee_DrawBar:Summoners()
    if not self.t.config.detailed.show and ((not self.t.config.summoners.showSelf and self.hero.isMe)
    or (not self.t.config.summoners.showAlly and self.hero.team == player.team and not self.hero.isMe)
    or (not self.t.config.summoners.showEnemy and self.hero.team == TEAM_ENEMY)) then
        return
    end
    local x, y, timerXOffset = self.hero.isMe and 113 or -13, -24, self.hero.isMe and 13 or -5
    if self.hero.team == TEAM_ENEMY then y = y + 1 end
    local spellOrder = { [1] = SUMMONER_1, [2] = SUMMONER_2 }
    local spriteOrder = {
        ["SummonerMana"] = 1,
        ["SummonerOdinGarrison"] = 2,
        ["SummonerHaste"] = 3,
        ["SummonerHeal"] = 4,
        ["SummonerRevive"] = 5,
        ["SummonerSmite"] = 6,
        ["SummonerBoost"] = 7,
        ["SummonerTeleport"] = 8,
        ["teleportcancel"] = 8,
        ["SummonerBarrier"] = 9,
        ["SummonerExhaust"] = 10,
        ["SummonerDot"] = 11,
        ["SummonerClairvoyance"] = 12,
        ["SummonerFlash"] = 13
    }
    for i = 1, 2 do
        for j, cCd in pairs(self.hero.cds) do
            if cCd.type == "SCD" and cCd.key == spellOrder[i] then
                local spell = self.hero:GetSpellData(cCd.key)
                local srcX = 13 * (spriteOrder[spell.name]-1)
                local maxCd = cCd.realMaxCd or spell.cd
                local srcY = 13 * floor(17 * (maxCd - min(maxCd, cCd.cd))/maxCd)
                self.t.sprites.spells.sprite:DrawEx(
                    Rect(srcX,srcY + (not self.hero.isMe and i == 1 and 1 or 0),srcX+13,srcY+13 - (not self.hero.isMe and i == 2 and 1 or 0)),                  --Rect
                    D3DXVECTOR3(0,0,0),                                                                                                                         --Center
                    D3DXVECTOR3(floor(self.barPos.x+x), floor(self.barPos.y+y+13*(i-1) + (not self.hero.isMe and i == 1 and 1 or 0)), 0),                       --Pos
                    0xFF)                                                                                                                                       --Opacity
                -- Timer:
                local cd = (cCd.cd > 0 and (cCd.cd <= self.t.config.summoners.showTimers or self.t.config.detailed.show or self.t.config.summoners.showAllTimers) and cCd.cd) or 0
                if cd > 0 then self:Timer(self.barPos.x + x + timerXOffset, self.barPos.y + y - 1 + 14*(i-1), cd, self.hero.isMe and "left" or "right") end
            end
        end
    end
end

function Trackeee_DrawBar:Timer(x, y, time, align)
    if time > 599 or time <= 0 then return end
    local frame, srcX, srcY
    local w, h = 29, 15
    local realw = (time < 10 and 18) or (time < 60 and 14) or 24
    x = align == "left" and x or align == "right" and x - realw or x - realw/2
    if time < 10 then
        frame = floor(time*10)
        srcY = 0
    else
        frame = floor(time)
        srcY = 135
    end
    srcX = (frame)%10*w
    srcY = srcY + floor(frame/10)*h
    self.t.sprites.myriad.sprite:DrawEx(
        Rect(srcX,srcY,srcX+w,srcY+h),                  --Rect
        D3DXVECTOR3(0,0,0),                             --Center
        D3DXVECTOR3(floor(x), floor(y), 0),   --Pos
        0xFF)                                           --Opacity
end

function Trackeee_DrawBar:SpellLevel(x, y, cd)
    if self.spellLevelAlpha > 0 then
        local level = cd.testLv or self.hero:GetSpellData(cd.key).level
        if cd.name == "R" and level == 3 then level = 5 end
        local w, h = 29, 15
        local realw = 29
        x = x - realw/2
        local srcX = 29*level
        local srcY = 1035
        self.t.sprites.myriad.sprite:DrawEx(
            Rect(srcX,srcY,srcX+w,srcY+h),                  --Rect
            D3DXVECTOR3(0,0,0),                             --Center
            D3DXVECTOR3(floor(x), floor(y), 0),   --Pos
            self.spellLevelAlpha)          --Opacity
    end
end

function Trackeee_DrawBar:Mana()
    local hero = self.hero
    if not self.t.config.oom.aSpell and not self.t.config.oom.aCombo
    or ((not self.t.config.oom.showSelf and self.hero.isMe)
    or (not self.t.config.oom.showAlly and self.hero.team == player.team and not self.hero.isMe)
    or (not self.t.config.oom.showEnemy and self.hero.team == TEAM_ENEMY)) then
        return
    end
    if not self.t.config.oom.aSpell and not self.t.config.oom.aCombo then return end
    if hero.parType == 0 or hero.parType == 1 then
        local comboCost = 0
        for i, cd in pairs(hero.cds) do
            if cd.type == "CD" then
                local cdOrder = (cd.name == "Q" and 1) or (cd.name == "W" and 2) or (cd.name == "E" and 3) or (cd.name == "R" and 4) or 0
                if cdOrder > 0 then
                    local manaCost = hero:GetSpellData(cd.key).mana
                    if cd.cd == 0 then comboCost = comboCost + manaCost end
                    if cd.OOM > 0 and self.t.config.oom.aSpell then
                        local c = self.t.config.oom.spellColor
                        cd.alphaOOM = (cd.OOM >= 0.5 and cd.alphaOOM < c[1] and min(c[1], cd.alphaOOM + 4)) or (cd.OOM < 0.5 and cd.alphaOOM > 0 and max(0,cd.alphaOOM-2)) or cd.alphaOOM
                        local newColor = ARGB(cd.alphaOOM, c[2], c[3], c[4])
                        local champManaBar = { w = self.manaBarWidth, h = 5, xOffset = 5, yOffset = -8 }
                        DrawLineT(self.barPos.x + champManaBar.xOffset + champManaBar.w*(manaCost/hero.maxMana),
                                  self.barPos.y + champManaBar.yOffset,
                                  self.barPos.x + champManaBar.xOffset + champManaBar.w*(manaCost/hero.maxMana),
                                  self.barPos.y + champManaBar.yOffset + champManaBar.h,
                                  1, newColor
                        )
                    elseif cd.OOM <= 0 then
                        cd.alphaOOM = 0
                    end
                end
            end
        end
        -- Drawing combo mana-cost mark on bars:
        if hero.mana < comboCost and self.t.config.oom.aCombo then
            local champManaBar = { w = self.manaBarWidth, h = 5, xOffset = 5, yOffset = -8 }
            local c = self.t.config.oom.comboColor
            DrawLineT(self.barPos.x + champManaBar.xOffset + champManaBar.w*(comboCost/hero.maxMana),
                      self.barPos.y + champManaBar.yOffset,
                      self.barPos.x + champManaBar.xOffset + champManaBar.w*(comboCost/hero.maxMana),
                      self.barPos.y + champManaBar.yOffset + champManaBar.h,
                      1, ARGB(c[1],c[2],c[3],c[4])
            )
         end
    end
end

function Trackeee_DrawBar:GetHealthText()
    local hero = self.hero
    local displayMode = (hero.isMe and self.t.config.namehealth.selfHpDisplay)
                            or (not hero.isMe and hero.team == player.team and self.t.config.namehealth.allyHpDisplay)
                            or (hero.team == TEAM_ENEMY and self.t.config.namehealth.enemyHpDisplay)
    return displayMode == 1 and ceil(hero.health) .. " / " .. ceil(hero.maxHealth)
        or displayMode == 2 and ceil(hero.health) .. ""
        or displayMode == 3 and ceil((hero.health/hero.maxHealth)*100) .. "%"
        or displayMode == 4 and ceil(hero.health * (1 + ((hero.team == TEAM_ENEMY and hero.armor*player.armorPenPercent-player.armorPen or hero.armor)/100))) .. ""
        or displayMode == 5 and ceil(hero.health * (1 + ((hero.team == TEAM_ENEMY and hero.magicArmor*player.magicPenPercent-player.magicPen or hero.magicArmor)/100))) .. ""
        or nil, displayMode
end

function Trackeee_DrawBar:HealthText()
    if self.t.config.namehealth.nameDisplayMode == 3 then return end
    local hero = self.hero
    local hpText = self:GetHealthText()
    if hpText then
        local c = self.t.config.namehealth.hpColor
        local o = 3 - self.t.config.namehealth.hpOutline
        local size = 12
        local champHealthBar = { w = self.manaBarWidth, xOffset = 5, yOffset = -21 }
        local alpha = hero.health/hero.maxHealth <= 0.65 and hero.health/hero.maxHealth >= 0.37 and 150 or 255
        if alpha == 255 and o < 3 then
            for i=-2,2,o do
                for j=-2,2,o do
                    DrawTextT(hpText, size, self.barPos.x + champHealthBar.xOffset + self.manaBarWidth/2 + i, self.barPos.y + champHealthBar.yOffset + j, ARGB(alpha,0,0,0), "center")
                end
            end
        end
        DrawTextT(hpText, size, self.barPos.x + champHealthBar.xOffset + self.manaBarWidth/2, self.barPos.y + champHealthBar.yOffset, ARGB(alpha,c[2],c[3],c[4]), "center")
    end
end

function Trackeee_DrawBar:Exp()
    if VIP_USER and self.t.config.showAllyExp and not self.t.lateStart and self.hero.team == player.team and not self.hero.isMe then 
        local minPct = 100/40
        local frame = floor(self.hero.expPct/minPct)
        --frame = floor(GetDrawClock(3,100)*100/minPct) -- test
        local srcX = (frame-1)%18*17
        local srcY = floor((frame-1)/18)*40
        self.t.sprites.expBar.sprite:DrawEx(
            Rect(srcX,srcY,srcX+17,srcY+40), --Rect
            D3DXVECTOR3(0,0,0), --Center
            D3DXVECTOR3(floor(self.barPos.x + self.bgWidth + 16), floor(self.barPos.y-31), 0), --Pos
            0xFF) --Opacity
    end
end

function Trackeee_DrawBar:TimeLine()
    local hero = self.hero
    local cfg = self.t.config.detailed.timeline
    if not self.t.config.detailed.show or self.onTop
    or (not cfg.showSelf and self.hero.isMe)
    or (not cfg.showAlly and self.hero.team == player.team and not self.hero.isMe)
    or (not cfg.showEnemy and self.hero.team == TEAM_ENEMY) then
        return
    end
    local lowSec, midSec, maxSec, longSec, longCdBarStartPos = cfg.lowSec, cfg.midSec, cfg.maxSec, cfg.longSec, cfg.longCdBarStartPos
    local markerHeight, timerHeight, textSize, lineColor = cfg.markerHeight, cfg.timerHeight, cfg.textSize, {cfg.color[1], cfg.color[2], cfg.color[3], cfg.color[4]}
    local TimeLine = {
        __init = function(self)
            local drawMarks = false
            for i, cd in pairs(hero.cds2) do
                if cd.cd > 0 then drawMarks = true break end
            end
            if (cfg.showMarksWhenCD and drawMarks) or not cfg.showMarksWhenCD then
                self:Marks()
            end
            self:Timers()
        end,
        Marks = function(self)
            self.Mark(lowSec, maxSec)
            self.Mark(midSec, maxSec)
            self.Mark(maxSec, maxSec)
        end,
        Mark = function(sec, maxSec)
            local logScale = logScale(sec, maxSec)
            DrawLineT(self.barPos.x + self.bgWidth * logScale * longCdBarStartPos * .01,
                     self.barPos.y - 23 - markerHeight,
                     self.barPos.x + self.bgWidth * logScale * longCdBarStartPos * .01,
                     self.barPos.y - 23,
                     1,
                     ARGB(lineColor[1], lineColor[2], lineColor[3], lineColor[4])
            )
            DrawText(""..sec,
                     12,
                     self.barPos.x + self.bgWidth * logScale * longCdBarStartPos * .01 - 6,
                     self.barPos.y - 34 - markerHeight,
                     ARGB(lineColor[1], lineColor[2], lineColor[3], lineColor[4])
            )
        end,
        Timers = function(self)
            for i, cd in pairs(hero.cds2) do
                self.Timer(i, lineColor)
            end
        end,
        Timer = function(cdIndex, cdColor)
            local cd = hero.cds2[cdIndex]
            if cd.cd > 0 then
                local x, y, timerText, textArea = self.barPos.x + cd.cdPos, self.barPos.y - 36, cd.timerText, cd.textArea

                -- Checking to which bar the cooldown belongs and calculating cooldown marker position (X coord)
                if cd.cd <= maxSec then
                    cd.cdPos = self.bgWidth * logScale((cd.cd > maxSec and maxSec) or cd.cd, maxSec) * longCdBarStartPos*.01
                else
                    cd.cdPos = self.bgWidth * longCdBarStartPos*.01 + self.bgWidth * logScale(((cd.cd > longSec and longSec) or cd.cd) - maxSec, longSec - maxSec) * (1 - longCdBarStartPos*.01)
                end

                -- Calculating fade-ins and fade-outs
                cd.alpha = (cd.cd >= 0.5 and cd.alpha < cdColor[1] and min(cdColor[1], cd.alpha + 12)) or (cd.cd < 0.5 and cd.alpha > 0 and max(0,cd.alpha - 2)) or cd.alpha
                local newColor = ARGB(cd.alpha, cdColor[2], cdColor[3], cdColor[4])

                -- Calculating cd.yOffsetTar (targeted yOffset)
                if not hero.cds2[cdIndex-1] or hero.cds2[cdIndex-1].cd <= 0 then
                    cd.yOffsetTar = 0
                end
                local shorterCd = hero.cds2[cdIndex+1]
                if shorterCd and shorterCd.cd > 0
                and floor(cd.cdPos) < floor(shorterCd.cdPos + shorterCd.textArea.x + 2)
                and floor(cd.cdPos) >= floor(shorterCd.cdPos) then
                    shorterCd.yOffsetTar = cd.yOffsetTar + textArea.y + 8
                end
                if shorterCd and shorterCd.cd > 0
                and floor(cd.cdPos) > floor(shorterCd.cdPos + shorterCd.textArea.x + 2) then
                    shorterCd.yOffsetTar = 0
                end

                -- Calculating slide up/down animations
                cd.yOffset = (cd.yOffset < cd.yOffsetTar and min(cd.yOffsetTar, cd.yOffset + 4)) or (cd.yOffset > cd.yOffsetTar and max(cd.yOffsetTar, cd.yOffset - 4)) or cd.yOffset

                -- Drawing cooldown marker
                DrawLineT(x, y + 12, x, y + 12 - timerHeight - cd.yOffset, 1, newColor)                                             -- Vertical line
                DrawLineT(x, y + 12 - timerHeight - cd.yOffset, x + textArea.x, y + 12 - timerHeight - cd.yOffset, 1, newColor)     -- Horizontal line
                DrawText(timerText, textSize, x, y + 12 - textArea.y - timerHeight - cd.yOffset, newColor)                                                 -- Cooldown text
                return true
            else
                cd.alpha = 0
                cd.yOffset = 0
                cd.yOffsetTar = 0
                return false
            end
        end,
    }
    setmetatable(TimeLine, {__call = TimeLine.__init })
    return TimeLine()
end

function Trackeee_DrawBar:Name()
    if self.t.config.namehealth.nameDisplayMode >= 2 and not self.t.config.detailed.show then
        local hero = self.hero
        local size = self.t.config.namehealth.nameSize
        local o = 3 - self.t.config.namehealth.nameOutline
        local outlineStep = size > 17 and o < 3 and 2 or o
        local c = self.t.config.namehealth.nameColor
        local champHealthBar = { w = self.manaBarWidth, xOffset = 5, yOffset = -41 }
        local y = self.barPos.y + champHealthBar.yOffset + self.hero.barData.TitleTextOffset.y + 13
        local nameText = ""
        if self.t.config.namehealth.nameDisplayMode == 3 then
            local hpText, mode = self:GetHealthText()
            nameText = mode == 0 and "" or ((mode == 4 or mode == 5) and "EHP: " or "HP: ") .. hpText
        else
            nameText = hero.charName
        end
        if outlineStep < 3 then
            for i=-2,2,outlineStep do
                for j=-2,2,outlineStep do
                    DrawTextT(nameText, size, self.barPos.x + champHealthBar.xOffset + self.manaBarWidth/2 + i, y + j, ARGB(c[1],0,0,0), "center", "bottom")
                end
            end
        end
        DrawTextT(nameText, size, self.barPos.x + champHealthBar.xOffset + self.manaBarWidth/2, y, ARGB(c[1],c[2],c[3],c[4]), "center", "bottom")
    end
end

function Trackeee_DrawBar:Extra()
    if self.t.config.detailed.show and self.t.config.detailed.showKdaCostCs then
        local hero = self.hero
        local xOffset = hero.isMe and -37 or 148
        local x = self.barPos.x + xOffset
        DrawLineT(x, self.barPos.y - 29, x, self.barPos.y + 6, 1, ARGB(255,255,255,255))
        DrawTextT("KDA: " .. hero.kills .. " / " .. hero.deaths .. " / " .. hero.assists, 12, x + (hero.isMe and -5 or 5), self.barPos.y - 31, ARGB(255,255,255,255), hero.isMe and "right" or "left")
        DrawTextT("COST: " .. hero.cost, 12, x + (hero.isMe and -5 or 5), self.barPos.y - 18, ARGB(255,255,255,255), hero.isMe and "right" or "left")
        DrawTextT("CS: " .. hero.minionKill, 12, x + (hero.isMe and -5 or 5), self.barPos.y - 5, ARGB(255,255,255,255), hero.isMe and "right" or "left")
    end
end

function Trackeee_DrawBar:IsDying()
    if VIP_USER then
        local hero = self.hero
        local incDmg = 0
        for j, dot in pairs(hero.dots) do
            if dot.endT > GetGameTimer() and dot.dmg then
                incDmg = incDmg + dot.dmg * floor(dot.endT - GetGameTimer())
            end
        end
        if incDmg >= hero.health then
            local uiBarOffset = 4 + (not self.hero.isMe and 1 or 0)     -- x
            local x = self.barPos.x + uiBarOffset + self.manaBarWidth/2
            local y = self.barPos.y - 20
            self.t.sprites.myriad.sprite:DrawEx(
                Rect(0,1061,52,1061+18),                                --Rect
                D3DXVECTOR3(0,0,0),                                     --Center
                D3DXVECTOR3(floor(x - 52/2), floor(y), 0),    --Pos
                0xFF)                                                   --Opacity
        end
    end
end

function Trackeee_DrawBar:Shopaholic()
    local hero = self.hero
    local cfg = self.t.config.shopaholic
    local Shopaholic = {
        __init = function(self)
            self.items = self.items or self:BuildItemTable()
            self.notifies = { duration = cfg.duration, add = {}, remove = {} }
            return self
        end,

        BuildItemTable = function(self)
            local items = {}
            for i = ITEM_1, ITEM_6 do
                local item = self:AddItem(i)
                if item then items[item.id] = item end
            end
            return items
        end,

        AddItem = function(self, slot)
            local item = hero:getItem(slot)
            return item and { id = item.id, sprite = GetItem(item.id):GetSprite(), stacks = item.stacks, changeStacks = 0} or nil
        end,
        
        Update = function(self)
            self.notifies.duration = cfg.duration
            if (not cfg.showSelf and hero.isMe)
            or (not cfg.showAlly and hero.team == player.team and not hero.isMe)
            or (not cfg.showEnemy and hero.team == TEAM_ENEMY) then
                return
            end
            if hero.bar.valid then
                local newItems = {}
                -- Iterating through new items:
                for i = ITEM_1, ITEM_6 do
                    local item = self:AddItem(i)
                    if item then
                        -- Checking if item is new, it got more stacks than before or simply calculating the amount of items:
                        if not self.items[item.id] or self.items[item.id].stacks < item.stacks then
                            item.state = 1
                            item.changeStacks = self.items[item.id] and item.stacks - self.items[item.id].stacks or 0
                            self.items[item.id] = item
                        end
                        -- Adding the item to temporary newItems table:
                        newItems[item.id] = item
                    end
                end
                -- Iterating through saved items:
                for i, item in pairs(self.items) do
                    if not newItems[i] then
                        item.state = 2
                    elseif item.stacks > newItems[i].stacks then
                        item.state = 3
                        item.changeStacks = item.stacks - newItems[i].stacks
                        item.stacks = newItems[i].stacks
                    end
                end
            end
            for i, item in pairs(self.items) do
                if item.state == 1 then         -- New item or new stack
                    if #self.notifies.add < 2 then
                        self:AddNotify(item, "add")
                        item.state = 0
                    end
                elseif item.state == 2 then     -- Item gone
                    if #self.notifies.remove < 2 then
                        self:AddNotify(item, "remove")
                        self.items[i] = nil
                    end
                elseif item.state == 3 then     -- Lost stack
                    if #self.notifies.remove < 2 then
                        self:AddNotify(item, "remove")
                        item.state = 0
                    end
                end
            end
        end,
        
        OnDraw = function(self)
            if (not cfg.showSelf and hero.isMe)
            or (not cfg.showAlly and hero.team == player.team and not hero.isMe)
            or (not cfg.showEnemy and hero.team == TEAM_ENEMY) then
                return
            end
            self.DrawNotifies(self.notifies)
        end,

        AddNotify = function(self, item, type)
            if type == "remove" and (cfg.consumablesOnly and consumablesTable[item.id] or not cfg.consumablesOnly) or type == "add" then
                local notify = { item = item, alpha = 0, yO = 0, step = 0, type = type, scale = 0.4 }
                self.notifies[type][#self.notifies[type]+1] = notify
            end
        end,

        DrawNotifies = function(notifies)
            local gameTime = self.t.gameTime
            local function DrawNotify(notify, i)
                if not notify.playT or notify.playT + notifies.duration > gameTime then
                    notify.playT = notifies[notify.type][i-1] and notifies[notify.type][i-1].playT == gameTime and notifies[notify.type][i-1].playT + 0.5 or notify.playT or gameTime
                    notify.order = notify.order or i-1
                    notify.step = gameTime >= notify.playT and min(1, (gameTime - notify.playT) / notifies.duration) or 0
                    local halfStep = notify.step > 0.5 and 2 - notify.step * 2 or notify.step * 2
                    notify.yO = Easing(notify.step, 0, 25)
                    notify.alpha = Easing(halfStep, 0, 255)
                    local yOffset = (notify.type == "add" and -75 or -50) - (self.onTop and max(self.bgHeight, self.qwerHeight+self.qwerOffset) or 0)
                    local xOffset = notify.type == "add" and 0 or hero.bar.bgWidth - notify.item.sprite.width * notify.scale + 2
                    local x = self.barPos.x + xOffset + (notify.type == "add" and 1 or -1)*(ceil(notify.item.sprite.width * notify.scale)+2)*notify.order
                    local y = self.barPos.y + (notify.type == "add" and 1 or -1)*notify.yO + yOffset
                    notify.item.sprite:SetScale(notify.scale, notify.scale)
                    notify.item.sprite:Draw(floor(x), floor(y), notify.alpha)
                    if notify.item.changeStacks > 0 then
                        DrawTextT(""..notify.item.changeStacks, 12, x, y, ARGB(notify.alpha,255,255,255))
                    elseif notify.item.stacks > 0 then
                        DrawTextT(""..notify.item.stacks, 12, x, y, ARGB(notify.alpha,255,255,255))
                    end
                else
                    notify.item.changeStacks = 0
                    notifies[notify.type][i] = nil
                end
            end

            for i, notify in pairs(notifies.add) do
                DrawNotify(notify, i)
            end
            for i, notify in pairs(notifies.remove) do
                DrawNotify(notify, i)
            end
        end,
    }
    setmetatable(Shopaholic, {__call = Shopaholic.__init })
    return Shopaholic()
end

function Trackeee_DrawBar:Test()
    --[[    States numbers
        print(READY)        --0, 1
        print(NOTLEARNED)   --2
        print(SUPRESSED)    --3
        print(COOLDOWN)     --4
        print(NOMANA)       --5
        print(INRANGE)      --6
        print(DANGER)       --7
    ]]
    local spellOrder = { _Q, _W, _E, _R }
    local states = { [0] = "READY", "1", "NOTLEARNED", "SUPRESSED", "COOLDOWN", "NOMANA", "INRANGE", "DANGER" }
    for i=1,4 do
        for j, cd in pairs(self.hero.cds) do
            if cd.key == spellOrder[i] then
                local state = states[cd.state]
                DrawText(cd.name .. ": " .. state, 12, self.barPos.x - 100, self.barPos.y + 15*i, ARGB(255,255,255,255))
                local realState = self.hero:CanUseSpell(cd.key)
                DrawText(cd.name .. ": " .. realState, 12, self.barPos.x - 200, self.barPos.y + 15*i, ARGB(255,255,255,255))
                local spellRange = self.hero:GetSpellData(cd.key).range
                DrawText(cd.name .. ": " .. spellRange, 12, self.barPos.x - 300, self.barPos.y + 15*i, ARGB(255,255,255,255))
            end
        end
    end
end


--UPDATEURL=
--HASH=6B3F674FB01D2CFEC65EC2EFF6308511
