<?php exit() ?>--by bothappy 83.38.21.248
if myHero.charName ~= "Leona" or not VIP_USER then return end

require "SALib"
require "Prodiction"

local Version = 0.02

function Variables()


	--Skills
	QReady, WReady, EReady, RReady = false, false, false, false
	ERange, EWidth, ESpeed, EDelay = 875, 80, 1200, 0.25
	RRange, RWidth, RSpeed, RDelay = 1200, 150, math.huge, 0.625

	--Prodiction
	Prodict = ProdictManager.GetInstance()
	ProdE = Prodict:AddProdictionObject(_E, ERange, ESpeed, EDelay, EWidth)
	ProdR = Prodict:AddProdictionObject(_R, RRange, RSpeed, RDelay, RWidth)

	--TS
	ts = TargetSelector(TARGET_LESS_CAST, RRange+100, DAMAGE_MAGIC, true)
	ts.name = "Leona"

	Priorities:Load()

	TSAdvanced = CombatHandler(ts)
	ORB = Orbwalker("FallbackOrbwalker")

	enemyHeroes = GetEnemyHeroes()

	--Other variables

	items = -- With entropy
    {
        BRK = {id=3153, range = 500, reqTarget = true, slot = nil },
        ETP = {id=3184, range = 350, reqTarget = true, slot = nil },
        BWC = {id=3144, range = 400, reqTarget = true, slot = nil },
        DFG = {id=3128, range = 750, reqTarget = true, slot = nil },
        HGB = {id=3146, range = 400, reqTarget = true, slot = nil },
        STD = {id=3131, range = 350, reqTarget = false, slot = nil},
		RSH = {id=3074, range = 350, reqTarget = false, slot = nil},
        TMT = {id=3077, range = 350, reqTarget = false, slot = nil},
        YGB = {id=3142, range = 350, reqTarget = false, slot = nil}
    }
end

function Menu()
	Menu = scriptConfig("BHLeona v"..Version, "BHLeona")
    Menu:addParam("Combo", "Cast Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
    Menu:addParam("UseItems", "Use Items", SCRIPT_PARAM_ONOFF, true)
    Menu:addParam("UseW", "Use W at Combo", SCRIPT_PARAM_ONOFF, true)
    Menu:addParam("UseR", "Use R at combo", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("G"))
    Menu:addParam("number", "Min Enemies to ult", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
    Menu:addParam("Circles", "Draw Circles", SCRIPT_PARAM_ONOFF, true)
    Menu:permaShow("Combo")
    Menu:permaShow("UseR")
    Menu:addTS(ts)
end
 
function OnLoad()
	SAUpdate = Updater("LeonaUpdate") 
	SAUpdate.LocalVersion = Version 
	SAUpdate.SCRIPT_NAME = "BHLeona" 
	SAUpdate.SCRIPT_URL = "https://bitbucket.org/BotHappy/bhscripts/raw/master/BHLeona.lua" 
	SAUpdate.PATH = BOL_PATH.."Scripts\\".."BHLeona.lua" 
	SAUpdate.HOST = "bitbucket.org" 
	SAUpdate.URL_PATH = "/BotHappy/bhscripts/raw/master/REV/LeonaRev.lua" 
	SAUpdate:Run()
	SAAuth = Auth("LeonaAuth")
	Variables()
	Menu()

	print("<font color='#FFFFFF'> >> BH Leona "..tostring(Version).." << </font>")
end

function CastE(unit, pos)
	if ValidTarget(unit) and pos ~= nil then
		if GetDistance(pos) < ERange then
			CastSpell(_E, pos.x, pos.z)
		end
	end
end

function CastR(unit)
	if ValidTarget(unit, RRange) then
		local pos = mostEnemies:GetCircleMEC(RRange, unit)
		if mostEnemies:CountEnemies(pos, RRange) > Menu.number-1 then
			CastSpell(_R, pos.x, pos.z)
		end
	end
end
 
function OnTick()
	if not target ~= nil then
		if ValidTarget(target) and Menu.Combo then
			Combo()
		end
	end
	Checks()
end

function UseItems(Target)
	UseRanduins()
	UseSolari()
    if not ValidTarget(Target) then return end
    for _,item in pairs(items) do
        item.slot = GetInventorySlotItem(item.id)
        if item.slot ~= nil then
            if item.reqTarget and GetDistance(Target) < item.range then
                CastOnObject(item.slot, Target)
            elseif not item.reqTarget then
                if (GetDistance(Target) - getHitBoxRadius(myHero) - getHitBoxRadius(Target)) < 50 then
                    CastSpell(item.slot)
                end
            end
        end
    end
end

function getHitBoxRadius(target)
    return GetDistance(target, target.minBBox)/2
end

function UseRanduins()
	Randuins = GetInventorySlotItem(3143)
	RanduinsReady = (Randuins ~= nil and myHero:CanUseSpell(Randuins))
	if RanduinsReady and Hero:CountInRange(500, myHero) > 2 then
		CastSpell(Randuins)
 	end
end

function UseSolari()
	Solari = GetInventorySlotItem(3190)
	SolariReady = (Solari ~= nil and myHero:CanUseSpell(Solari))
	if SolariReady and Hero:CountInRange(600, myHero) > 2 then
		CastSpell(Solari)
	end
end

function Combo()
	if Menu.UseItems then UseItems(target) end
	if GetDistance(target) < RRange and RReady and Menu.UseR then
		CastR(target)
	end
	if EReady then
		ProdE:GetPredictionCallBack(target, CastE)
	end
	if GetDistance(target) < 125 then
		if QReady and WReady then
			if Menu.UseW then
				CastSpell(_W)
			end
			CastSpell(_Q)
		elseif WReady and not QReady then
			if Menu.UseW then
				CastSpell(_W)
			end
		elseif QReady and not WReady then
			CastSpell(_Q)
		end
	end
end

function Checks()
	QReady = (myHero:CanUseSpell(_Q) == READY)
    WReady = (myHero:CanUseSpell(_W) == READY)
    EReady = (myHero:CanUseSpell(_E) == READY)
    RReady = (myHero:CanUseSpell(_R) == READY)

    IgniteReady = (IgniteSlot ~= nil and myHero:CanUseSpell(IgniteSlot) == READY)

    target = TSAdvanced:GetTarget()
end
 
function OnDraw()
	if Menu.Circles then
		if EReady then 
			DrawCircle(myHero.x, myHero.y, myHero.z, ERange, ARGB(255,95,159,159))
		end
		if RReady then
	        DrawCircle(myHero.x, myHero.y, myHero.z, RRange, ARGB(255,204,50,50))
		end
	end
	if target ~= nil then
		if GetDistance(target) < ERange and ValidTarget(target) then
        	DrawCircle(target.x, target.y, target.z, 45, ARGB(255,95,159,159))
   		end
   	end
end

function OnGainBuff(unit, buff)
    if unit == target and buff.type == 11 and buff.name == "leonazenithbladeroot" and Menu.Combo then
    	if Menu.UseW then
			CastSpell(_W)
		end
    	CastSpell(_Q)
    end
       
    -- if unit == target and buff.type == 5 and buff.name == "Stun" and Menu.Combo then
    --  	CastSpell(_R, unit.x, unit.z)
    -- end
end

