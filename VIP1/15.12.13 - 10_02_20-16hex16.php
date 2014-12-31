<?php exit() ?>--by 16hex16 105.236.17.194
local QRange, QSpeed, QDelay, QWidth = 1000, 1800, 0.251, 90
local AArange = 300
local Qrange = 1000
local Rrange = 600



function OnLoad()

PrintChat("Blitzcrank - A Must Grab Loaded.")
PrintChat("Version 0.01")
PrintChat("By Fluxy")

ts = TargetSelector(TARGET_LOW_HP_PRIORITY, Qrange, DAMAGE_MAGIC or DAMAGE_PHYSICAL)
ts.name = "Blitzcrank"
Target = ts.target

Menu()

Prodict = ProdictManager.GetInstance()
Pgrab = Prodict:AddProdictionObject(_Q, QRange, QSpeed, QDelay, QWidth)
PgrabCol = Collision(QRange, QSpeed, QDelay, QWidth)

end

function OnDraw()
 if not myHero.dead then
	if QAble and Config.draw.Qrangedraw then
		DrawCircle(myHero.x, myHero.y, myHero.z, Qrange, ARGB(Config.draw.QRangeColour[1], Config.draw.QRangeColour[2], Config.draw.QRangeColour[3], Config.draw.QRangeColour[4])) end
	if RAble and Config.draw.Rrangedraw then
		DrawCircle(myHero.x, myHero.y, myHero.z, Rrange, ARGB(Config.draw.RRangeColour[1], Config.draw.RRangeColour[2], Config.draw.RRangeColour[3], Config.draw.RRangeColour[4])) end
	if Config.draw.AArangedraw then
		DrawCircle(myHero.x, myHero.y, myHero.z, AArange, ARGB(Config.draw.AARangeColour[1], Config.draw.AARangeColour[2], Config.draw.AARangeColour[3], Config.draw.AARangeColour[4])) end
	
  end
  
  if Config.draw.Textdraw then 
	  
  DrawText("Blitzcrank - A Must Grab!", 18, 100, 100, ARGB(Config.draw.Textondraw[1], Config.draw.Textondraw[2], Config.draw.Textondraw[3], Config.draw.Textondraw[4]))
  
	if QAble then DrawText("Q Status", 18, 110, 120, ARGB(Config.draw.Textondraw[1], Config.draw.Textondraw[2], Config.draw.Textondraw[3], Config.draw.Textondraw[4])) else 
	 DrawText("Q Status", 18, 110, 120, ARGB(Config.draw.Textoffdraw[1], Config.draw.Textoffdraw[2], Config.draw.Textoffdraw[3], Config.draw.Textoffdraw[4])) end
  
    if WAble then DrawText("W Status", 18, 110, 140, ARGB(Config.draw.Textondraw[1], Config.draw.Textondraw[2], Config.draw.Textondraw[3], Config.draw.Textondraw[4])) else 
	 DrawText("W Status", 18, 110, 140, ARGB(Config.draw.Textoffdraw[1], Config.draw.Textoffdraw[2], Config.draw.Textoffdraw[3], Config.draw.Textoffdraw[4])) end
	 
	if EAble then DrawText("E Status", 18, 110, 160, ARGB(Config.draw.Textondraw[1], Config.draw.Textondraw[2], Config.draw.Textondraw[3], Config.draw.Textondraw[4])) else 
	 DrawText("E Status", 18, 110, 160, ARGB(Config.draw.Textoffdraw[1], Config.draw.Textoffdraw[2], Config.draw.Textoffdraw[3], Config.draw.Textoffdraw[4])) end
	 
	if RAble then DrawText("R Status", 18, 110, 180, ARGB(Config.draw.Textondraw[1], Config.draw.Textondraw[2], Config.draw.Textondraw[3], Config.draw.Textondraw[4])) else 
	 DrawText("R Status", 18, 110, 180, ARGB(Config.draw.Textoffdraw[1], Config.draw.Textoffdraw[2], Config.draw.Textoffdraw[3], Config.draw.Textoffdraw[4])) end
  
  end
end


function OnTick()
mtm()
Checks()
if Config.combo.combo then combo() end
ts:update()
end

function combo()	
	
if ts.target then	
 
		CastQ(ts.target)
		
		if AArange <= GetDistance(ts.target) then CastSpell(_E) end
	
		if AArange <= GetDistance(ts.target) and Config.combo.CastW then CastSpell(_W) end		
			
		if CountEnemies(myHero, Rrange) >= Config.combo.minenemyult then CastSpell(_R) end
	end
end	

			
			
function CastQ(target)
	local ProdictQ = Pgrab:GetPrediction(target)
	if ProdictQ and not PgrabCol:GetMinionCollision(ProdictQ) and GetDistance(ProdictQ) <= Qrange then
		CastSpell(_Q, ProdictQ.x, ProdictQ.z) end
end
		


function Checks()
	QAble = (myHero:CanUseSpell(_Q) == READY)
	WAble = (myHero:CanUseSpell(_W) == READY)
	EAble = (myHero:CanUseSpell(_E) == READY)
	RAble = (myHero:CanUseSpell(_R) == READY)
end

function CountEnemies(point, range)
        local ChampCount = 0
        for i = 1, heroManager.iCount, 1 do
                local enemyhero = heroManager:getHero(i)
                if myHero.team ~= enemyhero.team then
                        if GetDistance(enemyhero, point) <= range then
                                ChampCount = ChampCount + 1
                        end
                end
        end            
        return ChampCount
end

function KSwithR()

local damage = getDmg("R", enemy, myHero)

for i, enemy in ipairs(GetEnemyHeroes()) do
	if enemy and not enemy.dead and enemy.visible then
		if GetDistance(enemy) < 600 and enemy.health < damage then
			CastSpell(_R)
		end
	end
end	
end

function Menu()

Config = scriptConfig("Blitzcrank - A Must Grab","bc")


Config:addSubMenu("Combo Settings", "combo")

Config.combo:addTS(ts)
Config.combo:addParam("combo", "Blitzcrank Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
Config.combo:addParam("ksR","KS With R", SCRIPT_PARAM_ONOFF, true)
Config.combo:addParam("minenemyult","Number of enemies", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
Config.combo:addParam("castW","Use W in combo", SCRIPT_PARAM_ONOFF, false)


Config:addSubMenu("Draw Settings","draw")
Config.draw:addParam("QRangeColour", "Q Range Colour", SCRIPT_PARAM_COLOR, {207, 255, 204, 1})
Config.draw:addParam("Qrangedraw","Draw Q Range", SCRIPT_PARAM_ONOFF, true)
Config.draw:addParam("RRangeColour", "R Range Colour", SCRIPT_PARAM_COLOR, {207, 255, 204, 1})
Config.draw:addParam("Rrangedraw","Draw R Range", SCRIPT_PARAM_ONOFF, true)
Config.draw:addParam("AARangeColour", "AA Range Colour", SCRIPT_PARAM_COLOR, {207, 255, 204, 1})
Config.draw:addParam("AArangedraw","Draw AA Range", SCRIPT_PARAM_ONOFF, true)
Config.draw:addParam("Textondraw", "Default Text Colour", SCRIPT_PARAM_COLOR, {1, 76, 255, 1})
Config.draw:addParam("Textoffdraw", "Spell Down Text Colour", SCRIPT_PARAM_COLOR, {1, 255, 17, 1})
Config.draw:addParam("Textdraw","Draw Text ", SCRIPT_PARAM_ONOFF, true)



Config:addSubMenu("Extra Settings","extras")
Config.extras:addParam("MTM", "Move to Mouse", SCRIPT_PARAM_ONKEYDOWN, false, 32)
Config.extras:addParam("MTMT", "Move To Mouse Toggle", SCRIPT_PARAM_ONOFF, true)
Config.extras:addParam("dev", "Developer Mode", SCRIPT_PARAM_ONOFF, false)


if ts.target then
local pq  = Pgrab:GetPrediction(ts.target)
DrawCircle( pq.x, pq.y, pq.z, 50, 0xFF0000)
end

end

local function getHitBoxRadius(target)
	return GetDistance(target, target.minBBox)
end

function mtm()
if Config.extras.MTM == true and Config.extras.MTMT == true then
	myHero:MoveTo(mousePos.x, mousePos.z)
	end

if Config.extras.devmode == true and Config.MTM then PrintChat("mtm works") end
end

function OnWndMsg(msg,key)
  SC__OnWndMsg(msg,key)
end