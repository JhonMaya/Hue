<?php exit() ?>--by ragequit 174.53.87.155
--[[ The Wonderful Swain ]]
local Names = {
 "Sida",
 "mrsithsquirrel",
 "ragequit",
 "iRes",
 "MrSkii",
 "marcosd",
 "WTayllor",
 "pyrophenix",
 "dragonne",
 "xxgowxx",
 "kriksi",
 "serpicos",
 "Clamity",
 "xtony211",
 "Mercurial4991",
 "khicon",
 "igotcslol",
 "xthanhz",
 "Lienniar",
 "420yoloswag",
 "tacD",
 "zderekzz",
 "EZGAMER",
 "xpliclt",
 "omfgabriel",
 "hellking298",
 "Ex0tic123",
 "xxlarsyxx",
 "eway86",
 "UglyOldGuy",
 "Skribbs",
 "Meteoric",
 "Rayvagio",
}

local f = false
for _, Name in pairs(Names) do
 if GetUser() == Name then
  f = true
 end
end

if f then PrintChat("Welcome Alpha Testers") end


if myHero.charName ~= "Swain" then return end

class 'Plugin'

local SkillQ, SkillW, SkillE, SkillR
AutoCarry.Minions.EnemyMinions = minionManager(MINION_ENEMY, 950, myHero, MINION_SORT_HEALTH_ASC)
local QCD
local ECD
local WSPD
local QCD = nil
local ECD = nil
local WSPD = math.huge
local q = 1
local w = 2
local e = 3
local r = 4
local skilllevel
local skilllevel = { e,q,w,e,e,r,e,q,e,q,r,q,q,w,w,r,w,w, } 
local Debuffs = {}     

function debugs() 
 if ulton == true then PrintChat("ultOn") end
 if ulton == false then PrintChat("UltOff") end
 if QCD == true then PrintChat("Q on CD") end
 if ECD == true then PrintChat("E on CD") end
end 


function Plugin:__init()
  SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 625, "Decrepify", AutoCarry.SPELL_TARGETED, 0, false, false, 1.742, 235, 100, false)
  SkillW = AutoCarry.Skills:NewSkill(false, _W, 900, "Nevermove", AutoCarry.SPELL_CIRCLE, 0, false, false, WSPD, 700, 100, false) -- aims pretty well play with values see if we can get it a bit more accurate.
  SkillE = AutoCarry.Skills:NewSkill(false, _E, 625, "Torment", AutoCarry.SPELL_TARGETED, 0, false, false, 1.5, 200, 100, false)
  SkillR = AutoCarry.Skills:NewSkill(false, _R, 700, "Ravenous Flock", AutoCarry.SPELL_SELF, 0, false, false, 1.5, 200, 100, false)
  AutoCarry.Crosshair:SetSkillCrosshairRange(900)
  AdvancedCallback:bind("OnGainBuff", OnGainBuff)
  AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)

end

function Plugin:OnTick()
  --debug()
 

  Target = AutoCarry.GetAttackTarget()

  -- Farm
  if Menu.FarmQ and AutoCarry.Keys.MixedMode then
    DoFarm()
  end

  -- Auto-level
  if Menu.autos then
    autoLevelSetSequence(skilllevel)
  end

  -- Combat
  if Target then
    if AutoCarry.Keys.AutoCarry and Menu.Combo then
      Combo(Target)
    elseif AutoCarry.Keys.MixedMode and Menu.Harass then
      Harass(Target) 
    end
  end

  CalcDamage()

end   






--[[               COMBAT START                   ]]
function Combo(Target)
  SkillE:Cast(Target)
  SkillQ:Cast(Target)
  SkillW:Cast(Target)
  if Menu.AutoUlt and not hasUlt and GetDistance(Target) <= 666 then
    CastSpell(_R)
  elseif hasUlt and (not Target or GetDistance(Target) > 700) then
    CastSpell(_R)
  end   
end

function Harass(Target)
  SkillE:Cast(Target)
  SkillQ:Cast(Target)
  if Menu.HarassW then
    SkillW:Cast(Target)
  end
end 

-- Farm with Q and E if AA on cooldown
function DoFarm()
  if AutoCarry.Minions.KillableMinion and AutoCarry.Orbwalker:AttackReady() then
    local BlacklistMinion = AutoCarry.Minions.KillableMinion
  end
  if not AutoCarry.Orbwalker:AttackReady() and (myHero:CanUseSpell(_Q) == READY or myHero:CanUseSpell(_E) == READY) then
    for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
      if Minion ~= BlacklistMinion then
        if GetDistance(Minion) < 625 then
          if myHero:CanUseSpell(_Q) == READY and Menu.FarmQ then
            if Minion.health <= getDmg("Q", Minion, myHero) / 3 then
              CastSpell(_Q, Minion)
            end
          elseif myHero:CanUseSpell(_E) == READY and Menu.FarmE then
            if Minion.health <= getDmg("E", Minion, myHero) / 4 then
              CastSpell(_E, Minion)
            end
          end
        end
      end
    end
  end
end
  --[[           SPELL FARM END               ]]

function OnGainBuff(Unit, Buff)
  if Unit.isMe and Buff.name == "SwainMetamorphism" then
    hasUlt = true
  elseif Unit.team ~= myHero.team and Buff.name == "SwainTorment" then
    Debuffs[Unit.charName] = true
  end
end

function OnLoseBuff(Unit, Buff)
  if Unit.isMe and Buff.name == "SwainMetamorphism" then
    hasUlt = false
  elseif Unit.team ~= myHero.team and Buff.name == "SwainTorment" and Debuffs[Unit.charName] then
    Debuffs[Unit.charName] = nil
  end
end




--[[ Real damage calcs ]]

function GetBonusSpellDamage(Unit)
  if Debuffs[Unit.charName] then
    return (myHero:GetSpellData(_E).level * 3) + 5
  end
end


function CalcDamage()
  for _, Enemy in pairs(AutoCarry.Helper.EnemyTable) do
    if ValidTarget(Enemy) then
      local TotalDamage, Damage = GetDamage(Enemy)
      if Enemy.health < Damage then
        SetFloatText(Enemy, "Murder Him!!!!")
      elseif Enemy.health < TotalDamage then
        SetFloatText(Enemy, "Need cooldowns!")
      else
        SetFloatText(Enemy, "Can kill in "..math.ceil(Enemy.health - Damage).." health!")
      end
    end
  end
end



function GetDamage(target)
  local damage = 0
  local totalDamage = 0
  
  totalDamage, damage = GetDamages(3146, "HXG", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3144, "BWC", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3100, "LICHBANE", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3151, "LIANDRYS", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3188, "BLACKFIRE", nil, target, damage, totalDamage)
  
  if GetInventorySlotItem(3128) then
    if myHero:CanUseSpell(GetInventorySlotItem(3128)) == READY then
      damage = damage + (GetInventorySlotItem(3128) and getDmg("DFG",enemy,myHero) or 0)
      damage = damage * 1.2
    end
    totalDamage = totalDamage + (GetInventorySlotItem(3128) and getDmg("DFG",enemy,myHero) or 0)
    totalDamage = totalDamage * 1.2
  end
  
  totalDamage, damage = GetDamages(nil, "AD", nil, target, damage, totalDamage)
  
  totalDamage, damage = GetDamages(3153, "RUINEDKING", 2, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3057, "SHEEN", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3078, "TRINITY", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3025, "ICEBORN", nil, target, damage, totalDamage)

  
  if myHero:CanUseSpell(_Q) == READY then totalDamage, damage = GetDamages(nil, "Q", nil, target, damage, totalDamage) end
  if myHero:CanUseSpell(_E) == READY then totalDamage, damage = GetDamages(nil, "E", nil, target, damage, totalDamage) end
  if myHero:CanUseSpell(_R) == READY then totalDamage, damage = GetDamages(nil, "R", nil, target, damage, totalDamage) end
  if GetBonusSpellDamage(target) then
    totalDamage = (totalDamage / 100) * (100 + GetBonusSpellDamage(target))
    damage = (damage / 100) * (100 + GetBonusSpellDamage(target))
  end


  -- if target has E debuff
  
  return totalDamage, damage
end

function GetDamages(item, text, level, target, dmg, total)
  local damage = ((item and GetInventorySlotItem(item)) and getDmg (text, target, myHero, level) or 0)
  if item then 
    currentDamage = ((GetInventorySlotItem(item) and myHero:CanUseSpell(GetInventorySlotItem(item)) == READY) and getDmg (text, target, myHero, level) or 0)
  else 
    currentDamage = getDmg(text, target, myHero)
  end
  if text == "R" then
    damage = damage * 3
    currentDamage = currentDamage * 3
  end
  return total + damage, dmg + currentDamage
end


local Floats = {}
function SetFloatText(Unit, Text)
  local U = GetUnitFloatIndex(Unit)
  if U then
    Floats[U].Text = Text
  else
    table.insert(Floats, {Unit = Unit, Text = Text})
  end
end

function Plugin:OnDraw()
  DrawFloats()
end

function DrawFloats()
  if #Floats > 0 then
    PrintFloatText(Floats[1].Unit, 0, Floats[1].Text)
      table.remove(Floats, 1)
  end
end

function GetUnitFloatIndex(Unit)
  for i, Float in pairs(Floats) do
    if Float.Unit == Unit then 
      return i
    end
  end
end






Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "Nyan's Swain")
Menu:addParam("Combo", "Combo in Autocarry", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("Harass", "Harass in MixedMode", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("FarmQ", "Use Q to Farm", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("FarmE", "Use E to Farm", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("AutoUlt", "Auto Ult In AutoCarry", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassW", "Use W In Harass", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("autos", "Auto level skills", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("draw", "Draw Fight Prediction", SCRIPT_PARAM_ONOFF, true)
--Menu:addParam("AutoZ", "Auto Zhonya's", SCRIPT_PARAM_ONOFF, true)